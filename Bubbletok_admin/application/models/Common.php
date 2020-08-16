<?php

class Common extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('email');
	}

	public function select_all_result($table)
	{
		$this->db->select('*');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	public function deletedata($table, $where)
	{
		$this->db->where($where);
		return $this->db->delete($table);
	}

	public function select_where_result($table, $where)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function selectall_where_result($table, $where)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function update($table, $where, $data)
	{
		$this->db->where($where);
		return $this->db->update($table, $data);
	}

	public function insert($table, $data)
	{
		return $this->db->insert($table, $data);
	}

	public function get_total_rows($table, $where)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_data_all_desc($tablename, $where = array(), $field = '*', $ord_field)
	{
		$this->db->select($field);
		$this->db->from($tablename);
		$this->db->order_by($ord_field, "desc");
		$this->db->where($where);
		if ($query = $this->db->get()) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_data_all_asc($tablename, $where = array(), $field = '*', $ord_field)
	{
		$this->db->select($field);
		$this->db->from($tablename);
		$this->db->order_by($ord_field, "asc");
		$this->db->where($where);
		if ($query = $this->db->get()) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_data_row($tablename, $where = array(), $field = '*', $ord_field)
	{
		$this->db->select($field);
		$this->db->from($tablename);
		$this->db->order_by($ord_field, "desc");
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function insert_batch($table,$data){
		$insert = $this->db->insert_batch($table,$data);
		return $insert;
	}

	public function generateRandomString($length = 10) {
        $characters = "Joy-wallet-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+<>?:{};[],./";
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function query_run($sql)
	{
		$this->db->reset_query();
		$result = $this->db->query($sql);

		if ($result) {
			return $result->result_array();
		} else {
			echo 0;
		}
	}
}
