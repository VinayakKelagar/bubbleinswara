<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller{

     function __construct(){

        parent::__construct();
        $this->load->database();
        // $this->load->model('admin/User_model','Auser');
        $this->load->model('Common');
    }

    public function user_list()
    {
    	$this->load->view('admin/include/header');
    	$this->load->view('admin/user/user_list');
    	$this->load->view('admin/include/footer');
    }

    public function contact_list()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/user/contact_list');
        $this->load->view('admin/include/footer');
    }

    public function notification_list()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/user/notification_list');
        $this->load->view('admin/include/footer');
    }

    public function security()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/user/security');
        $this->load->view('admin/include/footer');
    }

    public function edit_user()
    {
        $user_id = base64_decode($this->uri->segment(3));
        $data['user_data'] = $this->Common->get_data_row('users', array('user_id'=>$user_id), $field = '*', 'user_id');
        
        $this->load->view('admin/include/header');
        $this->load->view('admin/user/edit_user',$data);
        $this->load->view('admin/include/footer');
    }

    public function view_user()
    {
        $user_id = base64_decode($this->uri->segment(3));
        $data['user_data'] = $this->Common->get_data_row('users', array('user_id'=>$user_id), $field = '*', 'user_id');
        
        $this->load->view('admin/include/header');
        $this->load->view('admin/user/view_user',$data);
        $this->load->view('admin/include/footer');
    }

    public function update_user()
    {
        extract($_POST);

        $fb_url= $this->input->post('fb_url');
        $insta_url= $this->input->post('insta_url');
        $youtube_url= $this->input->post('youtube_url');

        $data = array(
            'full_name'=>$full_name,
            'user_name'=>$user_name,
            'user_email'=>$user_email,
            'fb_url'=>$fb_url,
            'insta_url'=>$insta_url,
            'youtube_url'=>$youtube_url,
        );

        $where = array('user_id'=>$user_id);
        $update = $this->Common->update('users', $where, $data);
        print_r($update);
    }

    public function showUsers()
    {

        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }
        $valid_columns = array(
            0=>'user_id',
            1=>'full_name',
            2=>'user_name',
            3=>'user_email',
            4=>'user_mobile_no',
            5=>'login_type',
            6=>'identity',
            7=>'fb_url',
            8=>'insta_url',
            9=>'youtube_url',
            10=>'is_verify',
            11=>'created_date',
            12=>'is_verify',
            13=>'status'
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        
        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }                 
        }

        $this->db->limit($length,$start);
        $users = $this->db->get("users");

        $data = array();
        foreach($users->result() as $rows)
        {
            if ($rows->status == 0) {
                $status =  '<span class="badge badge-pill badge-danger">De-Active</span>';
            } elseif ($rows->status == 1) {
                $status =  '<span class="badge badge-pill badge-success">Active</span>';
            } 

            $user_id = $rows->user_id;
            $followers_where = array('to_user_id'=>$user_id);
            $followers_count = $this->Common->get_total_rows('followers', $followers_where);

            $following_where = array('from_user_id'=>$user_id);
            $following_count = $this->Common->get_total_rows('followers', $following_where);

            $total_videos = $this->Common->get_total_rows('post', array('user_id'=>$user_id));


            $data[]= array(
                $rows->full_name,
                $rows->user_name,
                $rows->user_email,
                $rows->identity,
                $followers_count,
                $following_count,
                $total_videos,
                '<a target="_blank" href="'.$rows->fb_url.'">'.$rows->fb_url.'</a>',
                '<a target="_blank" href="'.$rows->insta_url.'">'.$rows->insta_url.'</a>',
                '<a target="_blank" href="'.$rows->youtube_url.'">'.$rows->youtube_url.'</a>',
                $rows->created_date,
                $status,
                '<a href="'.base_url().'admin/edit_user/'.base64_encode($rows->user_id).'" class="settings"title="Edit User" data-toggle="tooltip" data-original-title="Manage User"><i class="i-cl-3 fa fa-edit"></i></a>
                <a href="'.base_url().'admin/view_user/'.base64_encode($rows->user_id).'" class="settings"title="View User" data-toggle="tooltip" data-original-title="View Matrix"><i class="i-cl-6 fa fa-eye"></i></a>
                '
            );  
        }
      
        // $total_user = $this->Common->get_total_rows('user', array());

        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }                 
        }

        $total_user = $this->db->get("users")->num_rows();

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_user,
            "recordsFiltered" => $total_user,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


    public function showNotification()
    {
        $draw = intval($this->input->post("draw"));
        $start = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order = $this->input->post("order");
        $search= $this->input->post("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }
        $valid_columns = array(
            0=>'notification_id',
            1=>'from_user',
            2=>'notification_type',
            3=>'address',
            4=>'amount',
            5=>'message',
            6=>'created_date'
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        
        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }                 
        }

        $this->db->limit($length,$start);
        $noti = $this->db->get("notification");

        $data = array();
        foreach($noti->result() as $rows)
        {
            $userdata = useridtodata($rows->from_user);

            if($rows->notification_type == "payment_success")
            {
                $type = '<span class="badge badge-pill badge-success">Payment Success</span>';
            }
            else if($rows->notification_type == "payment_failure")
            {
                $type = '<span class="badge badge-pill badge-danger">Payment Failure</span>';
            }
            else if($rows->notification_type == "service_update")
            {
                $type = '<span class="badge badge-pill badge-warning">Service Update</span>';
            }
            else if($rows->notification_type == "commission_payment")
            {
                $type = '<span class="badge badge-pill badge-success">Commission Payment</span>';
            }
            else
            {
                $type = '';
            }

            $data[]= array(
                $userdata['first_name']." ".$userdata['last_name'],
                $rows->address,
                $rows->amount,
                $rows->message,
                $type,
                $rows->created_date
            );  
        }
        // $total_noti = $this->Common->get_total_rows('notification', array());
        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }                 
        }

        $total_noti = $this->db->get("notification")->num_rows();

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_noti,
            "recordsFiltered" => $total_noti,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
}