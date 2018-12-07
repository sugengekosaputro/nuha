<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sesi_model extends CI_Model {

	private $tabel = 'tb_sesi';
	
	public function tampilSesi()
	{
        $this->db->order_by('tanggal','DESC');
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function tampilSesiById($id)
	{
		$this->db->where('id_sesi', $id);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
    }
    
    public function tampilSesiByTanggal($tgl)
	{
		$this->db->where('tanggal', $tgl);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function tampilSesiByRandom($random)
	{
		$this->db->where('random', $random);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function insertSesi($data)
	{
		$this->db->insert($this->tabel, $data);
		if ($this->db->affected_rows()>0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function updateSesi($id,$data)
	{
		$this->db->where('id_sesi', $id)
		->update($this->tabel, $data);
		
		if ($this->db->affected_rows()>0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function deleteSesi($id)
	{
		$this->db->where('id_sesi', $id)
		->delete($this->tabel);

		if ($this->db->affected_rows()>0) {
			return true;
		}else {
			return false;
		}
	}
}