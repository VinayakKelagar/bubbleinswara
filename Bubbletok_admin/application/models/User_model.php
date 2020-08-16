<?php

class User_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('email');
	}

	public function amazons3Upload( $image_name , $fileTempName, $upload_folder ){
 
	    $awsAccessKey = 'AKIAJJB6TPE52QA22HSQ'; //AWS account access key
	    $awsSecretKey = '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'; //AWS account secret key
	    $bucket_name  = 'takataknew';  //Bucket name 
	    $s3           = new S3($awsAccessKey, $awsSecretKey);
	    // $s3->putBucket($bucket_name);
	        
	    //move the file
	    if ($s3->putObjectFile($fileTempName, $bucket_name, $upload_folder.'/'.$image_name, S3::ACL_PUBLIC_READ)) {
	      return '1'; //return 1 it will success
	    }else{
	      return '7';
	    }
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

    public function notification_list($table,$where,$count,$start)
    {
    	$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$this->db->limit($count,$start);
		$this->db->order_by('notification_id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
}