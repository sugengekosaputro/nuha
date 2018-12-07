<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Santri_model extends CI_Model {

	private $tabel = 'tb_santri';
	
	public function tampilSantri()
	{
        $this->db->order_by('tanggal_masuk','ASC');
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function tampilSantriById($id)
	{
		$this->db->where('nis_lokal', $id);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function insertSantri($data)
	{
		$this->db->insert($this->tabel, $data);
		if ($this->db->affected_rows()>0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function updateSantri($id,$data)
	{
		$this->db->where('nis_lokal', $id)
		->update($this->tabel, $data);
		
		if ($this->db->affected_rows()>0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function deleteUser($id)
	{
		$this->db->where('nis_lokal', $id)
		->delete($this->tabel);

		if ($this->db->affected_rows()>0) {
			return true;
		}else {
			return false;
		}
	}
}

/* End of file User_model.php */