<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/Format.php';


class User extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('form_validation');
        $this->load->model('Common');
        $this->load->model('Post_model');
        $this->load->model('User_model');
        $this->load->model('Utils');
    }

    private function getUserModelKeys()
    {
        return [
            "full_name","user_name","user_email","user_mobile_no","bio","fb_url","insta_url","youtube_url"
            ];
    }

    public function file_upload_to_s3_post(){
        $this->load->library('S3');
 
        $image_name = $_FILES['file']['name'];
        $fileTempName = $_FILES['file']['tmp_name'];

   
            $upload_folder   = 'uploads';  //folder name
            // $fileTempName    =  '/var/www/html/fileuploadproject/uploads/email_logo.png'; //local image path (who we have to upload on s3)
            $bucket_name     =  'takataknew'; //Bucket name
            $awsstatus       =  $this->User_model->amazons3Upload($image_name, $fileTempName, $upload_folder); //call model function
            $awss3filepath   =  "http://".$bucket_name.'.'."s3.amazonaws.com/".$upload_folder.'/'.$image_name;
        print_r($awss3filepath);
     }


    public function registration_post()
    {
        error_reporting(0);
        $created_date = date('Y-m-d H:i:s');

        $this->form_validation->set_rules('full_name', 'full_name', 'required|trim',
            array('required'      => 'Oops ! full name is required.'
        ));

        $this->form_validation->set_rules('user_email', 'user_email','required|trim|valid_email',
            array('required'      => 'Oops ! email is required')
        );

        $this->form_validation->set_rules('device_token', 'device_token','required|trim',
            array('required'      => 'Oops ! device token is required')
        );

        $this->form_validation->set_rules('user_name', 'user_name','required|trim',
            array('required'      => 'Oops ! user name is required')
        );

        $this->form_validation->set_rules('identity', 'identity','required|trim',
            array('required'      => 'Oops ! identity is required')
        );

        $this->form_validation->set_rules('login_type', 'login_type','required|trim',
            array('required'      => 'Oops ! login type is required')
        );

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('full_name')))$response['message'] =form_error('full_name');
            if(!empty(form_error('user_email')))$response['message'] =form_error('user_email');
            if(!empty(form_error('device_token')))$response['message'] =form_error('device_token');
            if(!empty(form_error('identity')))$response['message'] =form_error('identity');
            if(!empty(form_error('user_name')))$response['message'] =form_error('user_name');
            if(!empty(form_error('login_type')))$response['message'] =form_error('login_type');
        }
        else
        {
            extract($_POST);

            $check_email_already = $this->User_model->check_identity_already($identity);
            if($check_email_already >= 1)
            {

                
                $up =  $this->Common->update('users', array('identity'=>$identity), array('device_token'=>$device_token));

                $where = array('identity'=>$identity);
                $userdata = $this->Common->get_data_row('users', $where, $field = '*','user_id');

                $where = array('user_id'=>$userdata['user_id']);
                $userdata = $this->Common->get_data_row('users', $where, $field = '*','user_id');
                
                $followers_where = array('to_user_id'=>$userdata['user_id']);
                $followers_count = $this->Common->get_total_rows('followers', $followers_where);

                $following_where = array('from_user_id'=>$userdata['user_id']);
                $following_count = $this->Common->get_total_rows('followers', $following_where);

                $my_post_likes = $this->Post_model->my_video_likes_count($user_id);

                $userdata['followers_count'] = $followers_count;
                $userdata['following_count'] = $following_count;
                $userdata['my_post_likes'] = $my_post_likes;

                $response = array(
                        'status' => TRUE,
                        'message' => 'User registration successful',
                        'data' => $userdata,
                    );
                $this->response($response, REST_Controller::HTTP_OK);
            }

            $token = date('Y-m-d H:i:s');
            $token = AUTHORIZATION::generateToken($token);
            
            $data = array(
                'full_name'=>$full_name,
                'user_email'=>$user_email,
                'token'=>$token,
                'device_token'=>$device_token,
                'user_name'=>$user_name,
                'identity'=>$identity,
                'created_date'=>$created_date,
                'login_type'=>$login_type,
            );

            $insert = $this->Common->insert('users', $data);

            if($insert)
            {
                $user_id = $this->db->insert_id();
                $where = array('user_id'=>$user_id);
                $userdata = $this->Common->get_data_row('users', $where, $field = '*','user_id');
                
                $followers_where = array('to_user_id'=>$user_id);
                $followers_count = $this->Common->get_total_rows('followers', $followers_where);

                $following_where = array('from_user_id'=>$user_id);
                $following_count = $this->Common->get_total_rows('followers', $following_where);

                $my_post_likes = $this->Post_model->my_video_likes_count($user_id);

                $userdata['followers_count'] = $followers_count;
                $userdata['following_count'] = $following_count;
                $userdata['my_post_likes'] = $my_post_likes;

                $response = array(
                    'status' => TRUE,
                    'message' => 'User registration successful',
                    // 'data' => $this->Utils->solveNulls($userdata),
                    'data' => $userdata,
                );
            }
            else
            {
                $response = array(
                    'status' => FALSE,
                    'message' => 'User registration failed',
                );
            }
        }        

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function verify_request_post()
    {
        $headers = $this->input->request_headers();
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => 401,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, 401); 
        }
        else
        {  
            $created_date = date('Y-m-d H:i:s');

            $this->form_validation->set_rules('id_number', 'id_number', 'required|trim',
                array('required'      => 'Oops ! id number is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('id_number')))$response['message'] =form_error('id_number');
            }
            else
            {
                $user_id = $verify_data['userdata']['user_id'];
                $count_where_approve = array('user_id'=>$user_id,'status'=>1);
                $count_approve = $this->Common->get_total_rows('verification_request', $count_where_approve);

                if($count_approve == 1)
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Verification request already aproved.',
                    );
                    $this->response($response, REST_Controller::HTTP_OK);
                    exit();
                }

                $count_where_pending = array('user_id'=>$user_id,'status'=>0);
                $count_pending = $this->Common->get_total_rows('verification_request', $count_where_pending);

                if($count_pending == 1)
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Your Verification request pending.',
                    );
                    $this->response($response, REST_Controller::HTTP_OK);
                    exit();
                }

                $id_number = $this->input->post('id_number') ? $this->input->post('id_number') : '';
                $name = $this->input->post('name') ? $this->input->post('name') : '';
                $address = $this->input->post('address') ? $this->input->post('address') : '';
                $created_date = date('Y-m-d H:i:s');
                $photo_id_image = "";

                if(!empty($_FILES['photo_id_image']['name'])){
                    $config['upload_path'] = 'uploads/';
                    $config['allowed_types'] = '*';
                    $config['file_name'] = rand(0,9999).$_FILES['photo_id_image']['name'];
                    
                    //Load upload library and initialize configuration
                    $this->load->library('upload',$config);
                    $this->upload->initialize($config);
                    
                    if($this->upload->do_upload('photo_id_image')){
                        $uploadData = $this->upload->data();
                        $photo_id_image = $uploadData['file_name'];
                    }
                  }

                $photo_with_id_image = "";

                if(!empty($_FILES['photo_with_id_image']['name'])){
                    $config['upload_path'] = 'uploads/';
                    $config['allowed_types'] = '*';
                    $config['file_name'] = rand(0,9999).$_FILES['photo_with_id_image']['name'];
                    
                    //Load upload library and initialize configuration
                    $this->load->library('upload',$config);
                    $this->upload->initialize($config);
                    
                    if($this->upload->do_upload('photo_with_id_image')){
                        $uploadData = $this->upload->data();
                        $photo_with_id_image = $uploadData['file_name'];
                    }
                  }
                
                $data = array(
                    'id_number'=>$id_number,
                    'user_id'=>$user_id,
                    'name'=>$name,
                    'address'=>$address,
                    'photo_id_image'=>$photo_id_image,
                    'photo_with_id_image'=>$photo_with_id_image,
                    'created_date'=>$created_date
                );

                $insert = $this->Common->insert('verification_request', $data);

                if($insert)
                {
                    $response = array(
                        'status' => TRUE,
                        'message' => 'Verification request successfully send.'
                    );
                }
                else
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Verification request send failed.',
                    );
                }
            }
        }      

        $this->response($response, REST_Controller::HTTP_OK);   
    }

    public function report_post()
    {
        $headers = $this->input->request_headers();
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => 401,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, 401); 
        }
        else
        {  
            $created_date = date('Y-m-d H:i:s');

            $this->form_validation->set_rules('report_type', 'report_type', 'required|trim',
                array('required'      => 'Oops ! report type is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('report_type')))$response['message'] =form_error('report_type');
            }
            else
            {
                $user_id = $verify_data['userdata']['user_id'];

                $report_type = $this->input->post('report_type') ? $this->input->post('report_type') : '';
                $post_id = $this->input->post('post_id') ? $this->input->post('post_id') : '';
                $reason = $this->input->post('reason') ? $this->input->post('reason') : '';
                $description = $this->input->post('description') ? $this->input->post('description') : '';
                $contact_info = $this->input->post('contact_info') ? $this->input->post('contact_info') : '';
                $created_date = date('Y-m-d H:i:s');

                $data = array(
                    'user_id'=>$user_id,
                    'post_id'=>$post_id,
                    'report_type'=>$report_type,
                    'reason'=>$reason,
                    'description'=>$description,
                    'contact_info'=>$contact_info,
                    'created_date'=>$created_date
                );

                $insert = $this->Common->insert('report', $data);

                if($insert)
                {
                    $response = array(
                        'status' => TRUE,
                        'message' => 'Report add successfully'
                    );
                }
                else
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Report add failed.',
                    );
                }
            }
        }      

        $this->response($response, REST_Controller::HTTP_OK);   
    }

    public function check_username_post()
    {
        $this->form_validation->set_rules('user_name', 'user_name', 'required|trim',
        array('required' => 'Oops ! user name is required.'
        ));

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('user_name')))$response['message'] =form_error('user_name');
        }
        else
        {
            extract($_POST);

            $where = array('user_name'=>$user_name);
            $allready = $this->Common->get_total_rows('users', $where);

            if($allready == 0)
            {
                $response = array(
                    'status' => TRUE,
                    'message' => 'Username generet successfully',
                );  
            }
            else
            {
                $response = array(
                    'status' => FALSE,
                    'message' => 'Username already exist',
                );
            }
        }
        
        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function user_details_post()
    {
        $created_date = date('Y-m-d H:i:s');

        $this->form_validation->set_rules('user_id', 'user_id', 'required|trim',
            array('required'      => 'Oops ! user id is required.'
        ));

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('user_id')))$response['message'] =form_error('user_id');
        }
        else
        {

            $user_id = $this->input->post('user_id') ? $this->input->post('user_id') : '';
            $my_user_id = $this->input->post('my_user_id') ? $this->input->post('my_user_id') : '';
            
            $is_count = $this->Common->get_total_rows('followers', array('from_user_id'=>$my_user_id,'to_user_id'=>$user_id));

            if($is_count > 0)
            {
                $is_count = 1;
            }
            else
            {
                $is_count = 0;
            }

            $user_details = $this->Common->select_where_result('users', array('user_id'=>$user_id));

            $followers_where = array('to_user_id'=>$user_id);
            $followers_count = $this->Common->get_total_rows('followers', $followers_where);

            $following_where = array('from_user_id'=>$user_id);
            $following_count = $this->Common->get_total_rows('followers', $following_where);

            $my_post_likes = $this->Post_model->my_video_likes_count($user_id);

            $user_details['followers_count'] = $followers_count;
            $user_details['following_count'] = $following_count;
            $user_details['my_post_likes'] = $my_post_likes;
            $user_details['is_following  '] = $is_count;
            

            $response = array(
                'status' => TRUE,
                'message' => 'User deatils get successfully',
                'data' => $user_details
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);   
    }

    public function user_update_post()
    {
        $headers = $this->input->request_headers();
        $verify_data = verify_request();

        if(isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => 401,
                'message' => 'Unauthorized Access!',
            );

            $this->response($response, 401); 
        }
        else
        {
            $user_id = $verify_data['userdata']['user_id'];

            $object = $_POST;
            extract($_POST);

            $keys = $this->getUserModelKeys();

            foreach ($keys as $key)
            {
                if(key_exists($key, $object)) {
                    $this->db->set($key, $object[$key]);
                }
            }

            $this->db->set('platform','android');

            $this->db->where("user_id", $user_id);
            $update = $this->db->update("users");

            if(!empty($_FILES['user_profile']['name'])){
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name'] = rand(0,9999).$_FILES['user_profile']['name'];
                
                //Load upload library and initialize configuration
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload('user_profile')){
                    $uploadData = $this->upload->data();
                    $user_profile = $uploadData['file_name'];
                    $image_data = array('user_profile'=>$user_profile);
                    $update_image =  $this->Common->update('users', array('user_id'=>$user_id), $image_data);
                }
              }

            if($update)
            {
                $where = array('user_id'=>$user_id);
                $userdata = $this->Common->get_data_row('users', $where, $field = '*','user_id');
                
                $followers_where = array('to_user_id'=>$user_id);
                $followers_count = $this->Common->get_total_rows('followers', $followers_where);

                $following_where = array('from_user_id'=>$user_id);
                $following_count = $this->Common->get_total_rows('followers', $following_where);

                $my_post_likes = $this->Post_model->my_video_likes_count($user_id);

                $userdata['followers_count'] = $followers_count;
                $userdata['following_count'] = $following_count;
                $userdata['my_post_likes'] = $my_post_likes;

                $response = array(
                    'status' => TRUE,
                    'message' => 'User details update successfully',
                    'data' => $userdata
                );
            }
            else
            {
                $response = array(
                    'status' => FALSE,
                    'message' => 'User details update failed'
                );
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function logout_get()
    {
        $headers = $this->input->request_headers();
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => 401,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, 401); 
        }
        else
        { 
            $user_id = $verify_data['userdata']['user_id'];

            $data = array(
                'device_token'=>''
            );

            $where = array('user_id'=>$user_id);
            $update = $this->Common->update('users', $where, $data);
            
            if($update)
            {
                $response = array(
                    'status' => TRUE,
                    'message' => 'Logout successfully'
                );
            }
            else
            {
                $response = array(
                    'status' => FALSE,
                    'message' => 'Logout failed'
                );
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function notification_list_post()
    {
        $headers = $this->input->request_headers();
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => 401,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, 401); 
        }
        else
        {
            $this->form_validation->set_rules('count', 'count', 'required|trim',
                array('required'      => 'Oops ! count is required.'
            ));

            $this->form_validation->set_rules('start', 'start', 'required|trim',
                array('required'      => 'Oops ! start is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('user_id')))$response['message'] =form_error('user_id');
                if(!empty(form_error('count')))$response['message'] =form_error('count');
                if(!empty(form_error('start')))$response['message'] =form_error('start');
            }
            else
            {
                extract($_POST);
                $user_id = $verify_data['userdata']['user_id'];
                
                $where = array('received_user_id'=>$user_id);
                $data = $this->User_model->notification_list('notification', $where,$count,$start);

                $ph = array();
                $x = 0;
                foreach ($data as $value) {

                    $user_userdata = get_row_data('users',array('user_id'=>$value['sender_user_id']));

                    $ph[$x]['full_name'] = $user_userdata['full_name'];
                    $ph[$x]['user_name'] = $user_userdata['user_name'];
                    $ph[$x]['user_profile'] = $user_userdata['user_profile'];
                    $ph[$x]['sender_user_id'] = $value['sender_user_id'];
                    $ph[$x]['received_user_id'] = $value['received_user_id'];
                    $ph[$x]['notification_type'] = $value['notification_type'];
                    $ph[$x]['message'] = $value['message'];
                    $ph[$x]['created_date'] = $value['created_date'];
                    $x++;
                }

                $response = array(
                    'status' => TRUE,
                    'message' => 'Notification list get successful',
                    'data' => $ph
                );     
            }

            $this->response($response, REST_Controller::HTTP_OK);
        }
    }

    public function notification_setting_post()
    {
        $headers = $this->input->request_headers();
        $verify_data = verify_request();

        if (isset($verify_data['status']) && $verify_data['status'] == 401) {
            $response = array(
                'status' => 401,
                'message' => 'Unauthorized Access!',
            );
            $this->response($response, 401); 
        }
        else
        {
            $user_id = $verify_data['userdata']['user_id'];
            $device_token = $this->input->post('device_token') ? $this->input->post('device_token') : '';

             $data = array(
                'device_token'=>$device_token
                );

                $where = array('user_id'=>$user_id);
                $update = $this->Common->update('users', $where, $data);

            if($update)
            {
                $response = array(
                    'status' => TRUE,
                    'message' => 'Setting save successfully'
                );
            }
            else
            {
                $response = array(
                    'status' => FALSE,
                    'message' => 'Setting save failed'
                );
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }
}
?>