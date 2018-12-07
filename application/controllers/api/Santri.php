<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Santri extends REST_Controller {
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('santri_model');
	}
    
    public function index_get()
    {
        $id = $this->uri->segment(3);
		$res = $this->santri_model->tampilSantri();
		$resId = $this->santri_model->tampilSantriById($id);

		if(empty($id)){
			if ($res) {
				$this->response($res,REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'Data Tidak Ada'
				],REST_Controller::HTTP_NOT_FOUND);
			}
		}else{
			if ($resId) {
				$this->response($resId,REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'Data Tidak Ada'
				],REST_Controller::HTTP_NOT_FOUND);
			}
		}
    }

    public function index_post()
    {
        $body = array(
            'nis_lokal' => $this->post('nis_lokal'),
            'nis_lokal_ernis' => $this->post('nis_lokal_ernis'),
            'nik' => $this->post('nik'),
            'nama_lengkap' => $this->post('nama_lengkap'),
            'tempat_lahir' => $this->post('tempat_lahir'),
            'tanggal_lahir' => $this->post('tanggal_lahir'),
            'jenis_kelamin' => $this->post('jenis_kelamin'),
            'agama' => $this->post('agama'),            
            'hobi' => $this->post('hobi'),
            'cita_cita' => $this->post('cita_cita'),
            'jumlah_sdr' => $this->post('jumlah_sdr'),
            'tanggal_masuk' => $this->post('tanggal_masuk'),
            'kls_madrasah_diniyah' => $this->post('kls_madrasah_diniyah'),
        );
        $insert = $this->santri_model->insertSantri($body);
        if($insert){
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

    public function index_put()
    {
        $id = $this->uri->segment(3);
        $body = array(
            'nis_lokal_ernis' => $this->put('nis_lokal_ernis'),
            'nik' => $this->put('nik'),
            'nama_lengkap' => $this->put('nama_lengkap'),
            'tempat_lahir' => $this->put('tempat_lahir'),
            'tanggal_lahir' => $this->put('tanggal_lahir'),
            'jenis_kelamin' => $this->put('jenis_kelamin'),
            'agama' => $this->put('agama'),            
            'hobi' => $this->put('hobi'),
            'cita_cita' => $this->put('cita_cita'),
            'jumlah_sdr' => $this->put('jumlah_sdr'),
            'tanggal_masuk' => $this->put('tanggal_masuk'),
            'kls_madrasah_diniyah' => $this->put('kls_madrasah_diniyah'),
        );
        $update = $this->santri_model->updateSantri($id,$body);
        if ($update) {
            $this->response([
                'status' => TRUE,
                'message' => 'Data Berhasil Diperbarui'
            ],REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Data Gagal Diperbarui'
            ],REST_Controller::HTTP_BAD_REQUEST);
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