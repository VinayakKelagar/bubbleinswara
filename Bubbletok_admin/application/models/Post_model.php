<?php

class Post_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('email');
	}

	public function post_list($start,$count)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->limit($count,$start);
		$this->db->order_by('post_id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function trending_post($start,$count)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->where('is_trending', 1);
		$this->db->limit($count,$start);
		$this->db->order_by('post_id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function post_following_list($start,$count,$user_id)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		
		$this->db->group_start();
		foreach($user_id as $val)
		{
		    $this->db->or_where('user_id',$val);
		}
		$this->db->group_end();
		
		$this->db->limit($count,$start);
		$this->db->order_by('post_id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function sound_list_search($keyword)
    {
    	$this->db->select('*');
		$this->db->from('sound');
		$this->db->where('status', 1);
		$this->db->where('added_by','admin');
		$this->db->group_start();
		$this->db->like('sound_title',$keyword);
		$this->db->group_end();
		$this->db->order_by('sound_id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function user_list_search($start,$count,$keyword)
    {
    	$this->db->select('*');
		$this->db->from('users');
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('full_name',$keyword);
		$this->db->or_like('user_name',$keyword);
		$this->db->group_end();
		$this->db->limit($count,$start);
		$this->db->order_by('user_id','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function hash_tag_search_video($start,$count,$keyword)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('post_hash_tag',$keyword);
		$this->db->or_like('post_description',$keyword);
		$this->db->group_end();
		$this->db->limit($count,$start);
		$this->db->order_by('video_likes_count','desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function hash_tag_videos($hash_tag)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('post_hash_tag',$hash_tag);
		$this->db->group_end();
		$this->db->order_by('video_likes_count','desc');
		$this->db->limit(10);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function single_hash_tag_videos($hash_tag,$count,$start)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('post_hash_tag',$hash_tag);
		$this->db->group_end();
		$this->db->order_by('video_likes_count','desc');
		$this->db->limit($count,$start);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function single_hash_tag_videos_count($hash_tag)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('post_hash_tag',$hash_tag);
		$this->db->group_end();
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }

    public function explore_hash_tag($start,$count)
    {
    	$this->db->select('*');
		$this->db->from('hash_tags');
		$this->db->where('move_explore', 1);
		$this->db->limit($count,$start);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function hash_tag_videos_count($hash_tag)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('post_hash_tag',$hash_tag);
		$this->db->group_end();
		$query = $this->db->get();
		$result = $query->num_rows();
		return $result;
    }

    public function user_videos($user_id,$count,$start)
    {
    	$this->db->select('*');
		$this->db->from('post');
		$this->db->where('status', 1);
		$this->db->where('user_id', $user_id);
		$this->db->order_by('post_id','desc');
		$this->db->limit($count,$start);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function user_likes_videos($user_id,$count,$start)
    {

    	$this->db->select('*');
		$this->db->from('likes');
		$this->db->join('post', 'post.post_id = likes.post_id');
		$this->db->where('likes.user_id',$user_id);
		$this->db->where('post.status', 1);
		$this->db->order_by('likes.like_id','desc');
		$this->db->limit($count,$start);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function sound_videos($sound_id,$count,$start)
    {

    	$this->db->select('*');
		$this->db->from('sound');
		$this->db->join('post', 'post.sound_id = sound.sound_id');
		$this->db->where('sound.sound_id',$sound_id);
		$this->db->where('post.status', 1);
		$this->db->where('sound.status', 1);
		$this->db->order_by('post.post_id','desc');
		$this->db->limit($count,$start);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }

    public function limited_sound_list($tablename, $where = array(), $field = '*', $ord_field)
	{
		$this->db->select($field);
		$this->db->from($tablename);
		$this->db->order_by($ord_field, "desc");
		$this->db->where($where);
		$this->db->limit(10);
		if ($query = $this->db->get()) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function my_video_likes_count($user_id)
	{
		$this->db->select('*');
		$this->db->from('post');
		$this->db->join('likes', 'likes.post_id = post.post_id');
		$this->db->where('post.user_id',$user_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function commet_list($tablename, $where, $start, $count)
	{
		$this->db->select('*');
		$this->db->from($tablename);
		$this->db->order_by('comments_id', "desc");
		$this->db->limit($count,$start);
		$this->db->where($where);
		if ($query = $this->db->get()) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function following_list($table, $where,$count,$start)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$this->db->limit($count,$start);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function follower_list($table, $where,$count,$start)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$this->db->limit($count,$start);
		$query = $this->db->get();
		return $query->result_array();
	}
}