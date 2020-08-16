<?php

class Wallet_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('email');
	}

	public function check_valid_referral($friend_referral)
	{
		$this->db->select('*');
        $this->db->from('user');
        $this->db->where('referral_code',$friend_referral);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        return $num_rows;
	}

	public function check_identity_already($identity)
	{
		$this->db->select('*');
        $this->db->from('users');
        $this->db->where('identity',$identity);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        return $num_rows;
	}

	public function userlist($keyword,$user_id)
    {
    	$this->db->select('user_id,first_name,last_name,email,mobile_no');
		$this->db->from('user');
		$this->db->group_start();
        $this->db->where('email LIKE','%'.$keyword.'%');
        $this->db->or_where('mobile_no LIKE','%'.$keyword.'%');
        $this->db->group_end();
		$this->db->where_not_in('user_id', $user_id);
		$this->db->where('is_verify', 1);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
    }
}