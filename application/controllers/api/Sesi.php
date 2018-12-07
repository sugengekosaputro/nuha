<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Sesi extends REST_Controller {
    
    public function __construct()
	{
		parent::__construct();
        $this->load->model('sesi_model');
        $this->load->model('absensi_model');
        $this->load->model('santri_model');
        $this->load->library('ciqrcode');
	}
    
    public function index_get()
    {
        $tgl = $this->uri->segment(3);
		$res = $this->sesi_model->tampilSesi();
        $resTgl = $this->sesi_model->tampilSesiByTanggal($tgl);

		if(empty($tgl)){
			if ($res) {
				$this->response($res,REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'Data Tidak Ada'
				],REST_Controller::HTTP_NOT_FOUND);
			}
		}else{
			if ($resTgl) {
				$this->response($resTgl,REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'Data Tidak Ada'
				],REST_Controller::HTTP_NOT_FOUND);
			}
		}
    }

    public function cek_get()
    {
        $tanggal = date('Y-m-d');
        $res = $this->sesi_model->tampilSesiByTanggal($tanggal);
        if ($res) {
            $this->response([
                'status' => FALSE,
                'message' => 'Tanggal Sudah Ada'
            ],REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        } else {
            $this->response([
                'status' => TRUE,
                'message' => 'Boleh Diisi'
            ],REST_Controller::HTTP_OK);
        }
    }


    public function index_post()
    {
        $tanggal = date('Y-m-d');
        
        $param = array('tanggal' => $tanggal);
        $cek_tanggal = $this->sesi_model->tampilSesiByTanggal($tanggal);

        if(!$cek_tanggal){
            $random = random_string('alnum', 5);

            $params['data'] = $random;
            $params['level'] = 'M';
            $params['size'] = 10;
            $params['savename'] = FCPATH.'qr/'.$tanggal.'.png';
            $this->ciqrcode->generate($params);

            $body = array(
                'tanggal' => date('Y-m-d'),
                'qr_path' => 'qr/'.$tanggal.'.png',
                'random' => $random,
            );
            $insertSesi = $this->sesi_model->insertSesi($body);
            if($insertSesi){
                $nis_lokal = $this->santri_model->tampilSantri();
                $id_sesi = $this->sesi_model->tampilSesiByTanggal($tanggal);

                foreach($nis_lokal as $nislok){
                    $nis[] = array(
                        'id_sesi' => $id_sesi[0]->id_sesi,
                        'nis_lokal' => $nislok->nis_lokal,
                        'status' => 'ada'
                    );
                }
                $insertAbsensi = $this->absensi_model->insertAbsensi($nis);
                if($insertAbsensi){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Data Berhasil Ditambahkan',
                    ],REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Data Gagal Ditambahkan',
                    ],REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        }else{
            $this->response([
                'status' => FALSE,
                'message' => 'Tanggal Sudah Ada',
            ],REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function index_put()
    {
        $random = $this->put('random');
		$nis_lokal = $this->put('nis_lokal');
        $hardware_id = $this->put('hardware_id');
        $keterangan = $this->put('keterangan');
        $tanggal = date('Y-m-d');
        $waktu = date('H:i:s');

        $get_id_sesi = $this->sesi_model->tampilSesiByTanggal($tanggal);
        $id_sesi_by_tgl = $get_id_sesi[0]->id_sesi;

        $cek_random = $this->sesi_model->tampilSesiByRandom($random);
        $id_sesi_by_random = $cek_random[0]->id_sesi;
        if ($cek_random) {
            //random tersedia
            //next cek hardware id
            $cek_absensi = $this->absensi_model->tampilAbsensiBySesiHardwareId($id_sesi_by_random,$hardware_id);
            if($cek_absensi){
                //Hardware id NOT NULL, Sudah Terdaftar pada sesi hari ini
                //jika nis berbeda -> cek nis
                $is_nis = $cek_absensi[0]->nis_lokal;
                $is_hardware = $cek_absensi[0]->hardware_id;
                if($is_nis == $nis_lokal && $is_hardware == $hardware_id){
                    //nis sistem sama dengan nis hp
                    //next bandingkan hardware id sistem dan device
                    //->cek status
                    $cek_status = $this->absensi_model->tampilAbsensiByHardwareIdNisSesi($hardware_id,$nis_lokal,$id_sesi_by_random);
                    //echo 'nis sistem : '.$is_nis.' == nis hp :'.$nis_lokal.' BOLEH SCAN';
                    $is_status = $cek_status[0]->status;
                    if($is_status == 'pergi'){
                       $body = array(
                           'status' => 'ada',
                           'keterangan' => null,
                           'log_time' => $waktu,
                        );
                        //ambil waktu terakhir log
                        $last_log = $cek_status[0]->log_time;
                        //cek detail absen where pergi = $last_log;
                        $cek_detail = $this->absensi_model->tampilDetailAbsensiByPergi($last_log);
                        
                        $id_detail = $cek_detail[0]->id_detail;
                        //update detail
                        //
                       $detail_absensi = array(
                           'kembali' => $waktu,
                       );
                       $prosesDetailAbsensi = $this->absensi_model->updateDetailAbsensi($id_detail,$detail_absensi);
                    }else if($is_status == 'ada'){
                        $body = array(
                            'status' => 'pergi',
                            'keterangan' => $keterangan,
                            'log_time' => $waktu,
                        );
                        //insert detail
                        $detail_absensi = array(
                            'id_absensi' => $cek_status[0]->id_absensi,
                            'pergi' => $waktu,
                            'kembali' => null,
                            'tanggal' => $tanggal,
                        );
                        $prosesDetailAbsensi = $this->absensi_model->insertDetailAbsensi($detail_absensi);
                    }
                    $update_absensi = $this->absensi_model->updateAbsensi($nis_lokal,$id_sesi_by_random,$body);
                    if($update_absensi){
                        //input detail
                        //cek status absensi
                        //jika ada -> set detail pergi = time(); insert data baru
                        //jika pergi -> set detail kembali = time(); update data lama
                        //
                        if($prosesDetailAbsensi){
                            $this->response([
                                'status' => TRUE,
                                'message' => 'Data Berhasil Diubah',
                            ],REST_Controller::HTTP_CREATED);
                        }
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Data Gagal Diubah',
                        ],REST_Controller::HTTP_BAD_REQUEST);
                    }
                }else{
                    //nis sistem TIDAK sama dengan nis hp
                    $this->response([
                        'status' => FALSE,
                        'message' => 'WARNING HP ANDA BERBEDA DENGAN SCAN SEBELUMNYA',
                    ],REST_Controller::HTTP_METHOD_NOT_ALLOWED);
                }
            }else{
                //Hardware id NULL, Belum Terdaftar pada sesi hari ini 
                //-> cek apa ada nis dengan sesi ini sama ? -> cek nis dan hardwarenya
                //-> jika nis tidak sama dengan hardware yang tercatat -> warning
                //-> else -> daftar baru
                $cek_absensi = $this->absensi_model->tampilAbsensiBySesiNis($id_sesi_by_random,$nis_lokal);
                $is_nis = $cek_absensi[0]->nis_lokal;
                $is_hardware = $cek_absensi[0]->hardware_id;
                if($is_hardware != null && $is_nis == $nis_lokal){
                    $this->response([
                        'status' => FALSE,
                        'message' => 'WARNING HP ANDA BERBEDA DENGAN SCAN SEBELUMNYA',
                    ],REST_Controller::HTTP_METHOD_NOT_ALLOWED);
                }else{
                    $body = array(
                        'status' => 'pergi',
                        'keterangan' => $keterangan,
                        'hardware_id' => $hardware_id,
                        'log_time' => $waktu,
                    );
                    $insert_absensi_new = $this->absensi_model->updateAbsensi($nis_lokal,$id_sesi_by_random,$body);
                    if($insert_absensi_new){
                        $cek_status = $this->absensi_model->tampilAbsensiByHardwareIdNisSesi($hardware_id,$nis_lokal,$id_sesi_by_random);
                        $detail_absensi = array(
                            'id_absensi' => $cek_status[0]->id_absensi,
                            'pergi' => $waktu,
                            'kembali' => null,
                            'tanggal' => $tanggal,
                        );
                        $prosesDetailAbsensi = $this->absensi_model->insertDetailAbsensi($detail_absensi);
                        if($prosesDetailAbsensi){
                            $this->response([
                                'status' => TRUE,
                                'message' => 'Data Detail Berhasil Ditambahkan',
                            ],REST_Controller::HTTP_CREATED);
                        }else{
                            $this->response([
                                'status' => FALSE,
                                'message' => 'Data Detail Gagal Ditambahkan',
                            ],REST_Controller::HTTP_BAD_REQUEST);
                        }
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Data Gagal Ditambahkan',
                        ],REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
           }
        } else {
            //random expired
            $this->response([
                'status' => FALSE,
                'message' => 'QR Expired'
            ],REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function index_delete()
    {
        $id = $this->uri->segment(3);
        $delete = $this->santri_model->deleteUser($id);
		if ($delete) {
			$this->response([
				'status' => TRUE,
				'message' => 'Data Berhasil Dihapus'
			],REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Data Gagal Dihapus'
			],REST_Controller::HTTP_BAD_REQUEST);
		}   
    }   
}