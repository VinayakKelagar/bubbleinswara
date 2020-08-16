<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends CI_Controller{

     function __construct(){

        parent::__construct();
        $this->load->database();
        $this->load->model('securedmyjoy/Login_model','Alogin');
        $this->load->model('Common');
    }


    public function ticket_list()
    {
        $this->load->view('securedmyjoy/include/header');
        $this->load->view('securedmyjoy/support/ticket_list');
        $this->load->view('securedmyjoy/include/footer');
    }

    public function view_ticket()
    {
        $ticket_id = base64_decode($this->uri->segment(3));
        
        $update_data = array('admin_view'=>1);
        $where = array('ticket_id'=>$ticket_id);

        $this->Common->update('ticket_reply', $where, $update_data);

        $data = $this->Common->select_where_result('ticket_support', array('ticket_id'=>$ticket_id));
        $this->load->view('securedmyjoy/include/header');
        $this->load->view('securedmyjoy/support/view_ticket',$data);
        $this->load->view('securedmyjoy/include/footer');
    }

     public function showTicket()
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
            0=>'ticket_id',
            1=>'user_id',
            2=>'subject',
            3=>'message',
            4=>'created_date',
            5=>'status'
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
        $support = $this->db->get("ticket_support");

        $data = array();
        foreach($support->result() as $rows)
        {
            $userdata = useridtodata($rows->user_id);

            if($rows->status == 1)
            {
                $status = '<div class="label cl-danger bg-danger-light">Closed</div>';
                $button = '<a href="JavaScript:Void(0);" style="cursor: not-allowed! important;" class="btn btn-small font-midium font-13 btn-rounded btn-danger" title="Closed Ticket">Closed</a>';
            }
            else if($rows->status == 0)
            {
                $status = '<div class="label cl-success bg-success-light">In progress</div>';
                $button = '<a href="JavaScript:Void(0);" onclick="manageticket(\''.$rows->ticket_id.'\');" class="btn btn-small font-midium font-13 btn-rounded btn-success" title="Close Ticket">Close Ticket</a>';
            }

            $where_count = array('ticket_id'=>$rows->ticket_id,'admin_view'=>0,'user_id !='=>0);
            $count = $this->Common->get_total_rows('ticket_reply', $where_count);
            if($count == 0)
            {
                $count = '<span class="a-nav__link-badge a-badge a-badge--accent">0</span>';
            }
            else
            {
                $count = '<span class="a-nav__link-badge a-badge a-badge--accent a-animate-blink">'.$count.'</span>';
            }


            $data[]= array(
                $userdata['first_name']." ".$userdata['last_name'],
                $rows->subject,
                $rows->message,
                $status,
                $rows->created_date,
                $count,
                '<a href="'.base_url().'securedmyjoy/view_ticket/'.base64_encode($rows->ticket_id).'" class="settings" title="Ticket Details" data-toggle="tooltip" data-original-title="Ticket Details"><i class="i-cl-6 fa fa-eye" style="font-size:20px;"></i></a>'
            );  
        }
        // $total_support = $this->Common->get_total_rows('support', array());

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

        $total_support = $this->db->get("ticket_support")->num_rows();

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_support,
            "recordsFiltered" => $total_support,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function manageticket()
    {
        $ticket_id = $this->input->post('ticket_id');
        $data = array('status'=>1);
        $where = array('ticket_id'=>$ticket_id);
        $update =  $this->Common->update('ticket_support', $where, $data);
        print_r($update);
    }

    public function get_chat_details()
    {
        $ticket_id = $this->input->post('ticket_id');
        $chat_details = $this->Common->selectall_where_result('ticket_reply', array('ticket_id'=>$ticket_id));
        foreach ($chat_details as $value) {
            if($value['user_id'] != 0)
            {
                ?>
                <li>
                    <div class="chat-content">
                        <h5 class="mrg-bot-5"><?=getUserName($value['user_id']);?></h5>
                        <div class="chating-box bg-light"><?=$value['reply_message'];?></div>
                    </div>
                    <div class="time-meta"><?=TimeAgo($value['created_date'], date("Y-m-d H:i:s"))?></div>
                </li>
                <?php
            }
            else
            {
                ?>
                <li class="reverse">
                    <div class="chat-time"><?=TimeAgo($value['created_date'], date("Y-m-d H:i:s"))?></div>
                    <div class="chat-content">
                        <h5 class="mrg-bot-5">Admin Replay</h5>
                        <div class="chating-box cl-white bg-primary"><?=$value['reply_message'];?></div>
                    </div>
                </li>
                <?php
            }
        }
    }

    public function replyticket()
    {
        $message = $this->input->post('message');
        $ticket_id = $this->input->post('ticket_id');
        $data = array(
            'ticket_id'=>$ticket_id,
            'reply_message'=>$message,
            'user_id'=>0,
            'created_date'=>date('Y-m-d H:i:s')
        );
        $insert = $this->Common->insert('ticket_reply', $data);
        if($insert)
        {
            $ticket_data = $this->Common->get_data_row('ticket_support', array('ticket_id'=>$ticket_id), $field = 'user_id,ticket_id', 'ticket_id');
            $user_id = $ticket_data['user_id'];

            $user_data = $this->Common->get_data_row('user', array('user_id'=>$user_id), $field = 'token,user_id,first_name,last_name,platform,device_token', 'user_id');

            $message = $message;
            $fcmtoken = $user_data['device_token'];
            $platform = $user_data['platform'];
            $data_type = 'ticket';
            $response = send_push($fcmtoken,$message,$platform,$data_type,$ticket_id);

            $support_data = array('status'=>1);
            $where = array('ticket_id'=>$ticket_id);
            $update =  $this->Common->update('ticket_support', $where, $support_data);
           
        }
        print_r($insert);
    }

    public function updateadminprofile()
    {

        $admin_id = $this->input->post('admin_id');
        $admin_name = $this->input->post('admin_name');
        $admin_email = $this->input->post('admin_email');
        $admin_password = $this->input->post('admin_password');

        $data = array(
            'admin_name'=>$admin_name,
            'admin_email'=>$admin_email,
            'admin_password'=>$admin_password,
        );

        $where = array(
            'admin_id'=>$admin_id
        );

       $update =  $this->Common->update('tbl_admin', $where, $data);
       print_r($update);
    }

    public function updateadminprofileimg()
    {
        $image_name = time().$_FILES['file']['name'];

        $upload_path =  "uploads/";


        $upload_url = base_url().$upload_path;

        // Upload file
        $w_uploadConfig = array(
            'upload_path' => $upload_path,
            'upload_url' => $upload_url,
            'allowed_types' => "*",
            'overwrite' => TRUE,
            'file_name' => $image_name
        );
            // var_dump($w_uploadConfig);
            // var_dump($image_name);

            $x =  $this->load->library('upload', $w_uploadConfig);

            if ($this->upload->do_upload('file')) {

                $imageurl = $this->upload->file_name;

                $result['image_url'] = $imageurl;

                $where = array(
                    'admin_id'=>$this->session->userdata('admin_id')
                );
                $updata = array(
                    'admin_profile'=>$imageurl
                );
                $update = $this->Common->update('tbl_admin',$where,$updata);
                if($update)
                {
                    print_r($update);
                }

            } else {
                 echo $this->upload->display_errors();
            }
    }

    public function update2fa()
    {
        $type = $this->input->post('type');
        $password = $this->input->post('password');
        $admin_id = $this->session->userdata('admin_id');
        $where = array('admin_id'=>$admin_id,'admin_password'=>$password);
        $count = $this->Common->get_total_rows('tbl_admin', $where);
       
        if($count > 0)
        {
            $userdata = $this->Common->select_where_result('tbl_admin', $where);
            $secret = $userdata['twofa_secret'];

            if(verify_code_google($secret,$this->input->post('two_fa_code'))){
                if($type == 'disable')
                {
                    $updata = array('is_twofa'=>0);
                    $update = $this->Common->update('tbl_admin',$where,$updata);
                    
                    $response['status'] = true;
                    $response['dismessage'] = '2-Factor Authentication disabled successfully!!!';
                  
                }
                else
                {
                    $updata = array('is_twofa'=>1);
                    $update = $this->Common->update('tbl_admin',$where,$updata);
                   
                    $response['status'] = true;
                    $response['dismessage'] = '2-Factor Authentication enabled successfully!!!';
                }
            } else {
                $response['status'] = false;
                $response['dismessage'] = 'Authentication Code Expired..';
            }
        } else {
            $response['status'] = false;
            $response['dismessage'] = 'Unauthorised User!!!';
        }
        echo json_encode($response);
    }
}