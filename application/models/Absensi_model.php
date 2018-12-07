<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {

    private $tabel = 'tb_absensi';
    private $tabel_detail = 'tb_absensi_detail';

    public function tampilAbsensiBySesiNis($id_sesi,$nis)
    {
        $this->db->where('id_sesi', $id_sesi);
        $this->db->where('nis_lokal', $nis);
        $query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }

    public function tampilAbsensiBySesiHardwareId($id_sesi,$hardware_id)
    {
        $this->db->where('id_sesi', $id_sesi);
        $this->db->where('hardware_id', $hardware_id);
        $query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }

    public function tampilAbsensiByHardwareSesi($hardware_id,$id_sesi)
    {
        $this->db->where('hardware_id', $hardware_id);
        $this->db->where('id_sesi', $id_sesi);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }

    public function tampilAbsensiByHardwareIdNisSesi($hardware_id,$nis,$id_sesi)
    {
        $this->db->where('hardware_id', $hardware_id);
        $this->db->where('nis_lokal', $nis);
        $this->db->where('id_sesi', $id_sesi);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }

    public function cekAkunIdByNis($nis,$id_sesi)
    {
        $this->db->where('nis_lokal', $nis);
        $this->db->where('id_sesi', $id_sesi);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }

	public function insertAbsensi($data)
	{
		$this->db->insert_batch($this->tabel, $data);
		if ($this->db->affected_rows()>0) {
			return true;
		} else {
			return false;
		}
    }
    
    public function updateAbsensi($nis,$id_sesi,$data)
    {
        $this->db->where('nis_lokal', $nis);
        $this->db->where('id_sesi', $id_sesi)
        ->update($this->tabel, $data);
        
        if ($this->db->affected_rows()>0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Model For tb_absensi_detail*/
    public function tampilDetailAbsensiByPergi($log_time)
    {
        $this->db->where('pergi', $log_time);
		$query = $this->db->get($this->tabel_detail);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }

    public function tampilDetailAbsensiByKembali($log_time)
    {
        $this->db->where('kembali', $log_time);
		$query = $this->db->get($this->tabel_detail);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }

    public function insertDetailAbsensi($data)
    {
        $this->db->insert($this->tabel_detail, $data);
		if ($this->db->affected_rows()>0) {
			return true;
		} else {
			return false;
		}
    }

    public function updateDetailAbsensi($id,$data)
    {
        $this->db->where('id_detail', $id)
        ->update($this->tabel_detail, $data);
        
        if ($this->db->affected_rows()>0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
