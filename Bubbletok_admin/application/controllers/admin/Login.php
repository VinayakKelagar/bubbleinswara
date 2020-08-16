<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{

     function __construct(){

        parent::__construct();
        $this->load->database();
        $this->load->model('admin/Login_model','Alogin');
        $this->load->model('Common');
    }

    public function index()
    {
        if(!empty($this->session->userdata('is_login_verify')))
        {
            redirect('admin/dashboard');
        }
        else
        {
            $this->load->view('admin/user/login');
        }
    }

    public function two_fa_verify()
    {
        $this->load->view('admin/user/2fa_verify');
    }

    public function jadminlogin()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $check = $this->Alogin->jadminlogin($username,$password);
        if($check == 1)
        {
            if($this->session->userdata('is_twofa') == 1)
            {
                print_r(2);
            }
            else
            {
                print_r(1);
            }
        }
        else
        {
            print_r(0);
        } 
    }

    public function verifylogin()
    {
        $admin_id = $this->session->userdata('admin_id');
        $where = array('admin_id'=>$admin_id);
        $count = $this->Common->get_total_rows('tbl_admin', $where);
            
        if($count > 0)
        {
            $admindata = $this->Common->select_where_result('tbl_admin', $where);
 
            $secret = $admindata['twofa_secret'];

            if(verify_code_google($secret,$_POST['twofa_code'])){
                $this->session->set_userdata('is_login_verify',1);
                $response['status'] = true;
                $response['message'] = "Login successfully";
            } else {
                $response['status'] = false;
                $response['message'] = "Authentication Code Expired..";
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Unauthorised User!!!";
        }

        echo json_encode($response);
    }

    public function jadminlogout()
    {
        $this->session->unset_userdata('admin_email');
        $this->session->unset_userdata('admin_password');
        $this->session->sess_destroy();
        redirect('/admin');
    }

    public function admin_profile()
    {
        $this->load->view('admin/include/header');
        $this->load->view('admin/admin_profile/admin_profile');
        $this->load->view('admin/include/footer');
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