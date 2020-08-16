<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/Format.php';


class Post extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('form_validation');
        $this->load->model('Common');
        $this->load->model('User_model');
        $this->load->model('Post_model');
        $this->load->model('Utils');
    }

    public function add_post_post()
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

            $this->form_validation->set_rules('is_orignal_sound', 'is_orignal_sound','required|trim',
                array('required'      => 'Oops ! hash tag is required')
            );

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('is_orignal_sound')))$response['message'] =form_error('is_orignal_sound');
            }
            else
            {
                extract($_POST);

                $post_hash_tag = $this->input->post('post_hash_tag');
                $post_description = $this->input->post('post_description');
                
                $user_id = $verify_data['userdata']['user_id'];

                $post_video = '';
                $post_image = '';
                $created_date = date('Y-m-d H:i:s');

                if(!empty($_FILES['post_video']['name'])){
                    $config['upload_path'] = 'uploads/';
                    $config['allowed_types'] = '*';
                    $config['file_name'] = rand(0,9999).$_FILES['post_video']['name'];
                    
                    //Load upload library and initialize configuration
                    $this->load->library('upload',$config);
                    $this->upload->initialize($config);
                    
                    if($this->upload->do_upload('post_video')){
                        $uploadData = $this->upload->data();
                        $post_video = $uploadData['file_name'];
                    }
                  }
                  

                  if(!empty($_FILES['post_image']['name'])){
                    $config['upload_path'] = 'uploads/';
                    $config['allowed_types'] = '*';
                    $config['file_name'] = rand(0,9999).$_FILES['post_image']['name'];
                    
                    //Load upload library and initialize configuration
                    $this->load->library('upload',$config);
                    $this->upload->initialize($config);
                    
                    if($this->upload->do_upload('post_image')){
                        $uploadData = $this->upload->data();
                        $post_image = $uploadData['file_name'];
                    }
                  }

                $data = array(
                    'post_description'=>$post_description,
                    'post_hash_tag'=>$post_hash_tag,
                    'user_id'=>$user_id,
                    'post_video'=>$post_video,
                    'post_image'=>$post_image,
                    'created_date'=>$created_date
                );

                $insert = $this->Common->insert('post', $data);
                if($insert)
                {
                    $last_insert_id = $this->db->insert_id();

                    if(!empty($post_hash_tag))
                    {
                        $array = explode(",",$post_hash_tag);
                        foreach($array as $arrayvalue)
                        {
                            $hash_tag_where = array('hash_tag_name'=>$arrayvalue);
                            $count = $this->Common->get_total_rows('hash_tags', $hash_tag_where);  
                          
                            if($count == 0)
                            {
                                $hash_data = array('hash_tag_name'=>$arrayvalue,'created_date'=>$created_date);
                                $insert_hash = $this->Common->insert('hash_tags', $hash_data);
                            }
                        }
                    }

                    if($is_orignal_sound == 1)
                    {
                        $sound_title = $this->input->post('sound_title');
                        $duration = $this->input->post('duration');
                        $singer = $this->input->post('singer');
                        $post_sound = '';
                        $sound_image = '';
                        if(!empty($_FILES['post_sound']['name'])){
                        $config['upload_path'] = 'uploads/';
                        $config['allowed_types'] = '*';
                        $config['file_name'] = rand(0,9999).$_FILES['post_sound']['name'];
                        
                        //Load upload library and initialize configuration
                        $this->load->library('upload',$config);
                        $this->upload->initialize($config);
                        
                        if($this->upload->do_upload('post_sound')){
                            $uploadData = $this->upload->data();
                            $post_sound = $uploadData['file_name'];
                        }
                    }

                    if(!empty($_FILES['sound_image']['name'])){
                        $config['upload_path'] = 'uploads/';
                        $config['allowed_types'] = '*';
                        $config['file_name'] = rand(0,9999).$_FILES['sound_image']['name'];
                        
                        //Load upload library and initialize configuration
                        $this->load->library('upload',$config);
                        $this->upload->initialize($config);
                        
                        if($this->upload->do_upload('sound_image')){
                            $uploadData = $this->upload->data();
                            $sound_image = $uploadData['file_name'];
                        }
                    }

                    $sound_data = array('sound'=>$post_sound,'sound_title'=>$sound_title,'duration'=>$duration,'singer'=>$singer,'sound_image'=>$sound_image);
                    $insert_sound = $this->Common->insert('sound', $sound_data);
                    $sound_id = $this->db->insert_id();
                   }
                   else if($is_orignal_sound == 0)
                   {
                        $sound_id = $this->input->post('sound_id'); 
                   }

                   $update_data = array('sound_id'=>$sound_id);
                   $where = array('post_id'=>$last_insert_id);
                   $this->Common->update('post', $where, $update_data);


                    $response = array(
                        'status' => TRUE,
                        'message' => 'Post added successfully'
                    );
                }
                else
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Post added failed'
                    );
                }
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function post_list_post()
    {
            $this->form_validation->set_rules('start', 'start','required|trim',
                array('required'      => 'Oops ! start required')
            );

            $this->form_validation->set_rules('count', 'count','required|trim',
                array('required'      => 'Oops ! count is required')
            );

            $this->form_validation->set_rules('type', 'type','required|trim',
                array('required'      => 'Oops ! type is required')
            );

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('start')))$response['message'] =form_error('start');
                if(!empty(form_error('count')))$response['message'] =form_error('count');
                if(!empty(form_error('type')))$response['message'] =form_error('type');
            }
            else
            {
                extract($_POST);
                
                $user_id = $this->input->post('user_id') ? $this->input->post('user_id') : '';

                if($type == "following")
                {
                    
                    $followers_id = $this->db->query("SELECT GROUP_CONCAT(to_user_id SEPARATOR ',') As to_user_ids from followers WHERE from_user_id = '".$user_id."'")->row_array();
                   
                    $followers_id = $followers_id['to_user_ids'];
                    
                    $followers_id = explode(',', $followers_id);
                    
                    $post_list = $this->Post_model->post_following_list($start,$count,$followers_id);
                    
                }
                else if($type == "trending")
                {
                    $post_list = $this->Post_model->trending_post($start,$count);
                }
                else
                {
                    $post_list = $this->Post_model->post_list($start,$count);
                }
                
                $x = 0;
                $ph = array();

                foreach ($post_list as $value) {

                    $post_userdata = get_row_data('users',array('user_id'=>$value['user_id']));
                    $post_comments_count = $this->Common->get_total_rows('comments', array('post_id'=>$value['post_id']));
                    $sound_where = array('sound_id'=>$value['sound_id']);
                    $sound_data = get_row_data('sound',$sound_where);

                    $likes_or_not_where = array('post_id'=>$value['post_id'],'user_id'=>$user_id);
                    $video_likes_or_not = $this->Common->get_total_rows('likes', $likes_or_not_where);

                    // $post_comments = $this->Common->get_data_all_asc('comments', array('post_id'=>$value['post_id']), $field = '*', 'comments_id');

                    // $c = 0;
                    // $comm = array();
                    // foreach ($post_comments as $comment_value) {
                    //     $where = array('user_id'=>$comment_value['user_id']);
                    //     $commentuserdata = get_row_data('users',$where);



                    //     $comm[$c]['comments_id'] = $comment_value['comments_id'];
                    //     $comm[$c]['comment'] = $comment_value['comment'];
                    //     $comm[$c]['created_date'] = $comment_value['created_date'];
                    //     $comm[$c]['user_id'] = $comment_value['user_id'];
                    //     $comm[$c]['full_name'] = $commentuserdata['full_name'];
                    //     $comm[$c]['user_name'] = $commentuserdata['user_name'];
                    //     $c++;
                    // }

                    $ph[$x]['post_id'] = $value['post_id'];
                    $ph[$x]['user_id'] = $value['user_id'];
                    $ph[$x]['full_name'] = $post_userdata['full_name'];
                    $ph[$x]['user_name'] = $post_userdata['user_name'];
                    $ph[$x]['user_profile'] = $post_userdata['user_profile'];
                    $ph[$x]['is_verify'] = $post_userdata['is_verify'];
                    $ph[$x]['is_trending'] = $value['is_trending'];
                    $ph[$x]['post_description'] = $value['post_description'];
                    $ph[$x]['post_hash_tag'] = $value['post_hash_tag'];
                    $ph[$x]['post_video'] = $value['post_video'];
                    $ph[$x]['post_image'] = $value['post_image'];

                    $ph[$x]['sound_id'] = $sound_data['sound_id'];
                    $ph[$x]['sound_title'] = $sound_data['sound_title'];
                    $ph[$x]['duration'] = $sound_data['duration'];
                    $ph[$x]['singer'] = $sound_data['singer'];
                    $ph[$x]['sound_image'] = $sound_data['sound_image'];
                    $ph[$x]['sound'] = $sound_data['sound'];
                    
                    $ph[$x]['post_likes_count'] = $value['video_likes_count'];
                    $ph[$x]['post_comments_count'] = $post_comments_count;
                    // $ph[$x]['post_comments'] = $comm;
                    $ph[$x]['post_view_count'] = $value['video_view_count'];

                    $ph[$x]['status'] = $value['status'];
                    $ph[$x]['created_date'] = $value['created_date'];
                    $ph[$x]['video_likes_or_not'] = $video_likes_or_not;
                    $x++;
                }

                $response = array(
                    'status' => TRUE,
                    'message' => 'Get video list successfully',
                    'data' => $ph,
                );
            }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function commet_list_post()
    {

        $created_date = date('Y-m-d H:i:s');

        $this->form_validation->set_rules('post_id', 'post_id', 'required|trim',
            array('required'      => 'Oops ! post id is required.'
        ));

        $this->form_validation->set_rules('start', 'start', 'required|trim',
            array('required'      => 'Oops ! start is required.'
        ));

        $this->form_validation->set_rules('count', 'count', 'required|trim',
            array('required'      => 'Oops ! count is required.'
        ));

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('post_id')))$response['message'] =form_error('post_id');
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);

             // $post_comments = $this->Common->get_data_all_asc('comments', array('post_id'=>$post_id), $field = '*', 'comments_id');

             $post_comments = $this->Post_model->commet_list('comments', array('post_id'=>$post_id),$start,$count);

                    $c = 0;
                    $comm = array();
                    foreach ($post_comments as $comment_value) {
                        $where = array('user_id'=>$comment_value['user_id']);
                        $commentuserdata = get_row_data('users',$where);

                        $comm[$c]['comments_id'] = $comment_value['comments_id'];
                        $comm[$c]['comment'] = $comment_value['comment'];
                        $comm[$c]['created_date'] = $comment_value['created_date'];
                        $comm[$c]['user_id'] = $comment_value['user_id'];
                        $comm[$c]['full_name'] = $commentuserdata['full_name'];
                        $comm[$c]['user_name'] = $commentuserdata['user_name'];
                        $comm[$c]['user_profile'] = $commentuserdata['user_profile'];
                        $comm[$c]['is_verify'] = $commentuserdata['is_verify'];
                        $c++;
                    }
            
            $response = array(
                'status' => TRUE,
                'message' => 'Comment list get successfully',
                'data' => $comm,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function like_unlike_post()
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

            $this->form_validation->set_rules('post_id', 'post_id', 'required|trim',
                array('required'      => 'Oops ! post id is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('post_id')))$response['message'] =form_error('post_id');
            }
            else
            {
                extract($_POST);
                $user_id = $verify_data['userdata']['user_id'];
                $full_name = $verify_data['userdata']['full_name'];
                $where = array('user_id'=>$user_id,'post_id'=>$post_id);
                $count = $this->Common->get_total_rows('likes', $where);
                $created_date = date('Y-m-d H:i:s');

                
                if($count == 1)
                {
                    $where = array('post_id'=>$post_id,'user_id'=>$user_id);
                    $delete = $this->Common->deletedata('likes', $where);

                    $count_update = $this->db->query('UPDATE post SET video_likes_count= video_likes_count-1 WHERE post_id ='.$post_id);

                    $response = array(
                        'status' => TRUE,
                        'message' => 'Unlike successful',
                    );
                }
                else
                {
                    $data = array('post_id'=>$post_id,'user_id'=>$user_id,'created_date'=>$created_date);
                    $insert = $this->Common->insert('likes', $data);

                    $post_data = get_row_data('post',array('post_id'=>$post_id));
                    $noti_user_id = $post_data['user_id'];
                    $noti_data = get_row_data('users',array('user_id'=>$noti_user_id));
                    $platform = $noti_data['platform'];
                    $device_token = $noti_data['device_token'];
                    $message = $full_name.' liked your video';

                    if($user_id != $noti_user_id)
                    {
                        $notificationdata = array(
                            'sender_user_id'=>$user_id,
                            'received_user_id'=>$noti_user_id,
                            'notification_type'=>'liked_video',
                            'message'=>$message,
                            'created_date'=>date('Y-m-d H:i:s')
                        );
                        $insert = $this->Common->insert('notification', $notificationdata);

                        send_push($device_token,$message,$platform);
                    }

                    $count_update = $this->db->query('UPDATE post SET video_likes_count= video_likes_count+1 WHERE post_id ='.$post_id);

                    $response = array(
                        'status' => TRUE,
                        'message' => 'Like successful',
                    );
                }
            }       
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function follow_unfollow_post()
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

            $this->form_validation->set_rules('to_user_id', 'to_user_id', 'required|trim',
                array('required'      => 'Oops ! to user id is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('to_user_id')))$response['message'] =form_error('to_user_id');
            }
            else
            {
                extract($_POST);
                $user_id = $verify_data['userdata']['user_id'];
                $full_name = $verify_data['userdata']['full_name'];
                $where = array('from_user_id'=>$user_id,'to_user_id'=>$to_user_id);
                $count = $this->Common->get_total_rows('followers', $where);
                $created_date = date('Y-m-d H:i:s');

                
                if($count == 1)
                {
                    $delete = $this->Common->deletedata('followers', $where);
                    $response = array(
                        'status' => TRUE,
                        'message' => 'Unfollow successful',
                    );
                }
                else
                {
                    $data = array('to_user_id'=>$to_user_id,'from_user_id'=>$user_id,'created_date'=>$created_date);
                    $insert = $this->Common->insert('followers', $data);

                    $noti_user_id = $to_user_id;
                    $noti_data = get_row_data('users',array('user_id'=>$noti_user_id));
                    $platform = $noti_data['platform'];
                    $device_token = $noti_data['device_token'];
                    $message = $full_name.' started following you';


                    $notificationdata = array(
                        'sender_user_id'=>$user_id,
                        'received_user_id'=>$noti_user_id,
                        'notification_type'=>'following',
                        'message'=>$message,
                        'created_date'=>date('Y-m-d H:i:s')
                    );

                    $insert = $this->Common->insert('notification', $notificationdata);

                    send_push($device_token,$message,$platform);

                    $response = array(
                        'status' => TRUE,
                        'message' => 'Follow successful',
                    );
                }
            }       
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function follower_list_post()
    {
        $this->form_validation->set_rules('user_id', 'user_id', 'required|trim',
            array('required'      => 'Oops ! to user id is required.'
        ));

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

            $where = array('to_user_id'=>$user_id);
            $data = $this->Post_model->follower_list('followers', $where,$count,$start);

            $ph = array();
            $x = 0;
            foreach ($data as $value) {

                $followers_data = $this->Common->select_where_result('users', array('user_id'=>$value['from_user_id']));

                $where = array('user_id'=>$value['from_user_id']);
                $userdata = $this->Common->get_data_row('users', $where, $field = '*','user_id');
                
                $followers_where = array('to_user_id'=>$value['from_user_id']);
                $followers_count = $this->Common->get_total_rows('followers', $followers_where);

                $following_where = array('from_user_id'=>$value['from_user_id']);
                $following_count = $this->Common->get_total_rows('followers', $following_where);

                $my_post_likes = $this->Post_model->my_video_likes_count($value['from_user_id']);

                $my_post_count = $this->Common->get_total_rows('post', $where);

                $ph[$x]['follower_id'] = $value['follower_id'];
                $ph[$x]['from_user_id'] = $value['from_user_id'];
                $ph[$x]['to_user_id'] = $value['to_user_id'];
                $ph[$x]['full_name'] = $followers_data['full_name'];
                $ph[$x]['user_name'] = $followers_data['user_name'];
                $ph[$x]['user_profile'] = $followers_data['user_profile'];
                $ph[$x]['is_verify'] = $followers_data['is_verify'];
                $ph[$x]['created_date'] = $value['created_date'];

                $ph[$x]['followers_count'] = $followers_count;
                $ph[$x]['following_count'] = $following_count;
                $ph[$x]['my_post_likes'] = $my_post_likes;
                $ph[$x]['my_post_count'] = $my_post_count;
                $x++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'Followers list successful',
                'data' => $ph
            );     
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function following_list_post()
    {
        $this->form_validation->set_rules('user_id', 'user_id', 'required|trim',
            array('required'      => 'Oops ! to user id is required.'
        ));

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

            $where = array('from_user_id'=>$user_id);
            $data = $this->Post_model->following_list('followers', $where,$count,$start);

            $ph = array();
            $x = 0;
            foreach ($data as $value) {

                $followers_data = $this->Common->select_where_result('users', array('user_id'=>$value['to_user_id']));

                $where = array('user_id'=>$value['to_user_id']);
                $userdata = $this->Common->get_data_row('users', $where, $field = '*','user_id');
                
                $followers_where = array('to_user_id'=>$value['to_user_id']);
                $followers_count = $this->Common->get_total_rows('followers', $followers_where);

                $following_where = array('from_user_id'=>$value['to_user_id']);
                $following_count = $this->Common->get_total_rows('followers', $following_where);

                $my_post_likes = $this->Post_model->my_video_likes_count($value['to_user_id']);

                $my_post_count = $this->Common->get_total_rows('post', $where);

                $ph[$x]['follower_id'] = $value['follower_id'];
                $ph[$x]['from_user_id'] = $value['from_user_id'];
                $ph[$x]['to_user_id'] = $value['to_user_id'];
                $ph[$x]['full_name'] = $followers_data['full_name'];
                $ph[$x]['user_name'] = $followers_data['user_name'];
                $ph[$x]['user_profile'] = $followers_data['user_profile'];
                $ph[$x]['is_verify'] = $followers_data['is_verify'];
                $ph[$x]['created_date'] = $value['created_date'];

                $ph[$x]['followers_count'] = $followers_count;
                $ph[$x]['following_count'] = $following_count;
                $ph[$x]['my_post_likes'] = $my_post_likes;
                $ph[$x]['my_post_count'] = $my_post_count;


                $x++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'Followers list successful',
                'data' => $ph
            );     
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function sound_list_post()
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
                extract($_POST);
                
                $user_id = $verify_data['userdata']['user_id'];

                $sound_category_list = $this->Common->get_data_all_desc('sound_category', array('status'=>1), $field = '*', 'sound_category_id');

                $ph = array();
                $x = 0;
                foreach ($sound_category_list as $value) {

                    $sound_list = $this->Post_model->limited_sound_list('sound', array('status'=>1,'sound_category_id'=>$value['sound_category_id'],'added_by'=>'admin'), $field = '*', 'sound_category_id');

                    $ph[$x]['sound_category_id'] = $value['sound_category_id'];
                    $ph[$x]['sound_category_name'] = $value['sound_category_name'];
                    $ph[$x]['sound_category_profile'] = $value['sound_category_profile'];
                    $ph[$x]['sound_list'] = $sound_list;
                    $x++;
                }
                
                $response = array(
                    'status' => TRUE,
                    'message' => 'Categorywise sound list get successfully',
                    'data' => $ph,
                );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function category_wise_sound_list_post()
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
            $this->form_validation->set_rules('sound_category_id', 'sound_category_id','required|trim',
                array('required'      => 'Oops ! sound category id is required')
            );

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('sound_category_id')))$response['message'] =form_error('sound_category_id');
            }
            else
            {
                extract($_POST);
                
                $user_id = $verify_data['userdata']['user_id'];

                $sound_list = $this->Post_model->limited_sound_list('sound', array('status'=>1,'sound_category_id'=>$sound_category_id,'added_by'=>'admin'), $field = '*', 'sound_category_id');
                
                $response = array(
                    'status' => TRUE,
                    'message' => 'Category wise sound list get successfully',
                    'data' => $sound_list,
                );
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function sound_list_search_post()
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
            $keyword = $this->input->post('keyword');

            $search_sound_list = $this->Post_model->sound_list_search($keyword);
            
            $response = array(
                'status' => TRUE,
                'message' => 'Search sound list get successfully',
                'data' => $search_sound_list,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function user_list_search_post()
    {
        $this->form_validation->set_rules('start', 'start','required|trim',
            array('required'      => 'Oops ! start required')
        );

        $this->form_validation->set_rules('count', 'count','required|trim',
            array('required'      => 'Oops ! count is required')
        );


        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);
            $keyword = $this->input->post('keyword');

            $user_list = $this->Post_model->user_list_search($start,$count,$keyword);

            $x = 0;
            $ph = array();
            foreach ($user_list as $value) {

                $where = array('user_id'=>$value['user_id']);
                $userdata = $this->Common->get_data_row('users', $where, $field = '*','user_id');
                
                $followers_where = array('to_user_id'=>$value['user_id']);
                $followers_count = $this->Common->get_total_rows('followers', $followers_where);

                $following_where = array('from_user_id'=>$value['user_id']);
                $following_count = $this->Common->get_total_rows('followers', $following_where);

                $my_post_likes = $this->Post_model->my_video_likes_count($value['user_id']);

                $my_post_count = $this->Common->get_total_rows('post', $where);

                $ph[$x]['user_id'] = $value['user_id'];
                $ph[$x]['full_name'] = $value['full_name'];
                $ph[$x]['user_name'] = $value['user_name'];
                $ph[$x]['user_email'] = $value['user_email'];
                $ph[$x]['user_mobile_no'] = $value['user_mobile_no'];
                $ph[$x]['user_profile'] = $value['user_profile'];
                $ph[$x]['is_verify'] = $value['is_verify'];
                $ph[$x]['bio'] = $value['bio'];
                $ph[$x]['fb_url'] = $value['fb_url'];
                $ph[$x]['insta_url'] = $value['insta_url'];
                $ph[$x]['youtube_url'] = $value['youtube_url'];
                $ph[$x]['followers_count'] = $followers_count;
                $ph[$x]['following_count'] = $following_count;
                $ph[$x]['my_post_likes'] = $my_post_likes;
                $ph[$x]['my_post_count'] = $my_post_count;
                $x++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'Search user list get successfully',
                'data' => $ph,
            );
        }

    $this->response($response, REST_Controller::HTTP_OK);
    }

    public function hash_tag_search_video_post()
    {
        $this->form_validation->set_rules('start', 'start','required|trim',
            array('required'      => 'Oops ! start required')
        );

        $this->form_validation->set_rules('count', 'count','required|trim',
            array('required'      => 'Oops ! count is required')
        );


        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);
            $keyword = $this->input->post('keyword');
            $my_user_id = $this->input->post('my_user_id');

            $hash_tag_search_video = $this->Post_model->hash_tag_search_video($start,$count,$keyword);

            $p_count = 0;
            $post_data = array();

            foreach ($hash_tag_search_video as $post_data_value) {

                $post_data_userdata = get_row_data('users',array('user_id'=>$post_data_value['user_id']));
                $post_data_comments_count = $this->Common->get_total_rows('comments', array('post_id'=>$post_data_value['post_id']));
                $sound_where = array('sound_id'=>$post_data_value['sound_id']);
                $sound_data = get_row_data('sound',$sound_where);

                $likes_or_not_where = array('post_id'=>$post_data_value['post_id'],'user_id'=>$my_user_id);
                $video_likes_or_not = $this->Common->get_total_rows('likes', $likes_or_not_where);

                $post_data[$p_count]['post_id'] = $post_data_value['post_id'];
                $post_data[$p_count]['user_id'] = $post_data_value['user_id'];
                $post_data[$p_count]['full_name'] = $post_data_userdata['full_name'];
                $post_data[$p_count]['user_name'] = $post_data_userdata['user_name'];
                $post_data[$p_count]['user_profile'] = $post_data_userdata['user_profile'];
                $post_data[$p_count]['is_verify'] = $post_data_userdata['is_verify'];
                $post_data[$p_count]['is_trending'] = $post_data_value['is_trending'];
                $post_data[$p_count]['post_description'] = $post_data_value['post_description'];
                $post_data[$p_count]['post_hash_tag'] = $post_data_value['post_hash_tag'];
                $post_data[$p_count]['post_video'] = $post_data_value['post_video'];
                $post_data[$p_count]['post_image'] = $post_data_value['post_image'];
                $post_data[$p_count]['sound_id'] = $sound_data['sound_id'];
                $post_data[$p_count]['sound_title'] = $sound_data['sound_title'];
                $post_data[$p_count]['duration'] = $sound_data['duration'];
                $post_data[$p_count]['singer'] = $sound_data['singer'];
                $post_data[$p_count]['sound_image'] = $sound_data['sound_image'];
                $post_data[$p_count]['sound'] = $sound_data['sound'];
                $post_data[$p_count]['post_likes_count'] = $post_data_value['video_likes_count'];
                $post_data[$p_count]['post_comments_count'] = $post_data_comments_count;
                $post_data[$p_count]['post_view_count'] = $post_data_value['video_view_count'];
                $post_data[$p_count]['status'] = $post_data_value['status'];
                $post_data[$p_count]['created_date'] = $post_data_value['created_date'];
                $post_data[$p_count]['video_likes_or_not'] = $video_likes_or_not;
                $p_count++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'Search hash tag video get successfully',
                'data' => $post_data,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function explore_hash_tag_video_post()
    {
        $this->form_validation->set_rules('start', 'start','required|trim',
            array('required'      => 'Oops ! start required')
        );

        $this->form_validation->set_rules('count', 'count','required|trim',
            array('required'      => 'Oops ! count is required')
        );


        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);
            $explore_hash_tag = $this->Post_model->explore_hash_tag($start,$count);
            $my_user_id = $this->input->post('my_user_id');
            
            $x = 0;
            $ph = array();
            foreach ($explore_hash_tag as $value) {
                
                $hash_tag_videos = $this->Post_model->hash_tag_videos($value['hash_tag_name']);

                $hash_tag_videos_count = $this->Post_model->hash_tag_videos_count($value['hash_tag_name']);
                

                $p_count = 0;
                $post_data = array();

                foreach ($hash_tag_videos as $post_data_value) {

                    $post_data_userdata = get_row_data('users',array('user_id'=>$post_data_value['user_id']));
                    $post_data_comments_count = $this->Common->get_total_rows('comments', array('post_id'=>$post_data_value['post_id']));
                    $sound_where = array('sound_id'=>$post_data_value['sound_id']);
                    $sound_data = get_row_data('sound',$sound_where);

                    $likes_or_not_where = array('post_id'=>$post_data_value['post_id'],'user_id'=>$my_user_id);
                    $video_likes_or_not = $this->Common->get_total_rows('likes', $likes_or_not_where);

                    $post_data[$p_count]['post_id'] = $post_data_value['post_id'];
                    $post_data[$p_count]['user_id'] = $post_data_value['user_id'];
                    $post_data[$p_count]['full_name'] = $post_data_userdata['full_name'];
                    $post_data[$p_count]['user_name'] = $post_data_userdata['user_name'];
                    $post_data[$p_count]['user_profile'] = $post_data_userdata['user_profile'];
                    $post_data[$p_count]['is_verify'] = $post_data_userdata['is_verify'];
                    $post_data[$p_count]['is_trending'] = $post_data_value['is_trending'];
                    $post_data[$p_count]['post_description'] = $post_data_value['post_description'];
                    $post_data[$p_count]['post_hash_tag'] = $post_data_value['post_hash_tag'];
                    $post_data[$p_count]['post_video'] = $post_data_value['post_video'];
                    $post_data[$p_count]['post_image'] = $post_data_value['post_image'];
                    $post_data[$p_count]['sound_id'] = $sound_data['sound_id'];
                    $post_data[$p_count]['sound_title'] = $sound_data['sound_title'];
                    $post_data[$p_count]['duration'] = $sound_data['duration'];
                    $post_data[$p_count]['singer'] = $sound_data['singer'];
                    $post_data[$p_count]['sound_image'] = $sound_data['sound_image'];
                    $post_data[$p_count]['sound'] = $sound_data['sound'];
                    $post_data[$p_count]['post_likes_count'] = $post_data_value['video_likes_count'];
                    $post_data[$p_count]['post_comments_count'] = $post_data_comments_count;
                    $post_data[$p_count]['post_view_count'] = $post_data_value['video_view_count'];
                    $post_data[$p_count]['status'] = $post_data_value['status'];
                    $post_data[$p_count]['created_date'] = $post_data_value['created_date'];
                    $post_data[$p_count]['video_likes_or_not'] = $video_likes_or_not;
                    $p_count++;
                }

                $ph[$x]['hash_tag_name'] = $value['hash_tag_name'];
                $ph[$x]['hash_tag_videos_count'] = $hash_tag_videos_count;
                $ph[$x]['hash_tag_videos'] = $post_data;
                $x++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'Explore hash tag video get successfully',
                'data' => $ph,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function single_hash_tag_video_post()
    {
        $this->form_validation->set_rules('hash_tag', 'hash_tag','required|trim',
            array('required'      => 'Oops ! hash tag is required')
        );

        $this->form_validation->set_rules('start', 'start','required|trim',
            array('required'      => 'Oops ! start is required')
        );

        $this->form_validation->set_rules('count', 'count','required|trim',
            array('required'      => 'Oops ! count is required')
        );

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('hash_tag')))$response['message'] =form_error('hash_tag');
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);
            $my_user_id = $this->input->post('my_user_id');

            $hash_tag_videos = $this->Post_model->single_hash_tag_videos($hash_tag,$count,$start);
            $post_count = $this->Post_model->single_hash_tag_videos_count($hash_tag);

            $p_count = 0;
            $post_data = array();

            foreach ($hash_tag_videos as $post_data_value) {

                $likes_or_not_where = array('post_id'=>$post_data_value['post_id'],'user_id'=>$my_user_id);
                $video_likes_or_not = $this->Common->get_total_rows('likes', $likes_or_not_where);

                $post_data_userdata = get_row_data('users',array('user_id'=>$post_data_value['user_id']));
                $post_data_comments_count = $this->Common->get_total_rows('comments', array('post_id'=>$post_data_value['post_id']));
                $sound_where = array('sound_id'=>$post_data_value['sound_id']);
                $sound_data = get_row_data('sound',$sound_where);

                $post_data[$p_count]['post_id'] = $post_data_value['post_id'];
                $post_data[$p_count]['user_id'] = $post_data_value['user_id'];
                $post_data[$p_count]['full_name'] = $post_data_userdata['full_name'];
                $post_data[$p_count]['user_name'] = $post_data_userdata['user_name'];
                $post_data[$p_count]['user_profile'] = $post_data_userdata['user_profile'];
                $post_data[$p_count]['is_verify'] = $post_data_userdata['is_verify'];
                $post_data[$p_count]['is_trending'] = $post_data_value['is_trending'];
                $post_data[$p_count]['post_description'] = $post_data_value['post_description'];
                $post_data[$p_count]['post_hash_tag'] = $post_data_value['post_hash_tag'];
                $post_data[$p_count]['post_video'] = $post_data_value['post_video'];
                $post_data[$p_count]['post_image'] = $post_data_value['post_image'];
                $post_data[$p_count]['sound_id'] = $sound_data['sound_id'];
                $post_data[$p_count]['sound_title'] = $sound_data['sound_title'];
                $post_data[$p_count]['duration'] = $sound_data['duration'];
                $post_data[$p_count]['singer'] = $sound_data['singer'];
                $post_data[$p_count]['sound_image'] = $sound_data['sound_image'];
                $post_data[$p_count]['sound'] = $sound_data['sound'];
                $post_data[$p_count]['post_likes_count'] = $post_data_value['video_likes_count'];
                $post_data[$p_count]['post_comments_count'] = $post_data_comments_count;
                $post_data[$p_count]['post_view_count'] = $post_data_value['video_view_count'];
                $post_data[$p_count]['status'] = $post_data_value['status'];
                $post_data[$p_count]['created_date'] = $post_data_value['created_date'];
                $post_data[$p_count]['video_likes_or_not'] = $video_likes_or_not;
                $p_count++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'Hash tag wise video get successfully',
                'data' => $post_data,
                'post_count' => $post_count
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function increase_video_view_post()
    {
        $created_date = date('Y-m-d H:i:s');

        $this->form_validation->set_rules('post_id', 'post_id', 'required|trim',
            array('required'      => 'Oops ! post id is required.'
        ));

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('post_id')))$response['message'] =form_error('post_id');
        }
        else
        {
            extract($_POST);
            
            $count_update = $this->db->query('UPDATE post SET video_view_count= video_view_count+1 WHERE post_id ='.$post_id);

            $response = array(
                'status' => TRUE,
                'message' => 'Videos views update successful',
            );
          
        }       

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function add_comment_post()
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
            $this->form_validation->set_rules('post_id', 'post_id', 'required|trim',
                array('required'      => 'Oops ! post id is required.'
            ));

            $this->form_validation->set_rules('comment', 'comment', 'required|trim',
                array('required'      => 'Oops ! comment is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('post_id')))$response['message'] =form_error('post_id');
                if(!empty(form_error('comment')))$response['message'] =form_error('comment');
            }
            else
            {
                $user_id = $verify_data['userdata']['user_id'];
                $full_name = $verify_data['userdata']['full_name'];
                extract($_POST);

                $data = array(
                    'post_id'=>$post_id,
                    'user_id'=>$user_id,
                    'comment'=>$comment,
                    'created_date'=>date('Y-m-d H:i:s')
                );

                $insert = $this->Common->insert('comments', $data);
                
                if($insert)
                {

                    $post_data = get_row_data('post',array('post_id'=>$post_id));
                    $noti_user_id = $post_data['user_id'];
                    $noti_data = get_row_data('users',array('user_id'=>$noti_user_id));
                    $platform = $noti_data['platform'];
                    $device_token = $noti_data['device_token'];
                    $message = $full_name.' commented on your videos';


                    if($user_id != $noti_user_id)
                    {
                        $notificationdata = array(
                            'sender_user_id'=>$user_id,
                            'received_user_id'=>$noti_user_id,
                            'notification_type'=>'comment_video',
                            'message'=>$message,
                            'created_date'=>date('Y-m-d H:i:s')
                        );

                        $insert = $this->Common->insert('notification', $notificationdata);

                        send_push($device_token,$message,$platform);
                    }

                    $response = array(
                        'status' => TRUE,
                        'message' => 'Comment add successfully'
                    );
                }
                else
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Comment add failed'
                    );
                }
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function user_videos_post()
    {
        $this->form_validation->set_rules('user_id', 'user_id','required|trim',
            array('required'      => 'Oops ! user id required')
        );

        $this->form_validation->set_rules('start', 'start','required|trim',
            array('required'      => 'Oops ! start is required')
        );

        $this->form_validation->set_rules('count', 'count','required|trim',
            array('required'      => 'Oops ! count is required')
        );

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('user_id')))$response['message'] =form_error('user_id');
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);
            $my_user_id = $this->input->post('my_user_id');
            $user_videos = $this->Post_model->user_videos($user_id,$count,$start);

            $p_count = 0;
            $post_data = array();

            foreach ($user_videos as $post_data_value) {

                $post_data_userdata = get_row_data('users',array('user_id'=>$post_data_value['user_id']));
                $post_data_comments_count = $this->Common->get_total_rows('comments', array('post_id'=>$post_data_value['post_id']));
                $sound_where = array('sound_id'=>$post_data_value['sound_id']);
                $sound_data = get_row_data('sound',$sound_where);

                $likes_or_not_where = array('post_id'=>$post_data_value['post_id'],'user_id'=>$my_user_id);
                $video_likes_or_not = $this->Common->get_total_rows('likes', $likes_or_not_where);

                $post_data[$p_count]['post_id'] = $post_data_value['post_id'];
                $post_data[$p_count]['user_id'] = $post_data_value['user_id'];
                $post_data[$p_count]['full_name'] = $post_data_userdata['full_name'];
                $post_data[$p_count]['user_name'] = $post_data_userdata['user_name'];
                $post_data[$p_count]['user_profile'] = $post_data_userdata['user_profile'];
                $post_data[$p_count]['is_verify'] = $post_data_userdata['is_verify'];
                $post_data[$p_count]['is_trending'] = $post_data_value['is_trending'];
                $post_data[$p_count]['post_description'] = $post_data_value['post_description'];
                $post_data[$p_count]['post_hash_tag'] = $post_data_value['post_hash_tag'];
                $post_data[$p_count]['post_video'] = $post_data_value['post_video'];
                $post_data[$p_count]['post_image'] = $post_data_value['post_image'];
                $post_data[$p_count]['sound_id'] = $sound_data['sound_id'];
                $post_data[$p_count]['sound_title'] = $sound_data['sound_title'];
                $post_data[$p_count]['duration'] = $sound_data['duration'];
                $post_data[$p_count]['singer'] = $sound_data['singer'];
                $post_data[$p_count]['sound_image'] = $sound_data['sound_image'];
                $post_data[$p_count]['sound'] = $sound_data['sound'];
                $post_data[$p_count]['post_likes_count'] = $post_data_value['video_likes_count'];
                $post_data[$p_count]['post_comments_count'] = $post_data_comments_count;
                $post_data[$p_count]['post_view_count'] = $post_data_value['video_view_count'];
                $post_data[$p_count]['status'] = $post_data_value['status'];
                $post_data[$p_count]['created_date'] = $post_data_value['created_date'];
                $post_data[$p_count]['video_likes_or_not'] = $video_likes_or_not;
                $p_count++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'User videos get successfully',
                'data' => $post_data,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function user_likes_videos_post()
    {
        $this->form_validation->set_rules('user_id', 'user_id','required|trim',
            array('required'      => 'Oops ! user id required')
        );

        $this->form_validation->set_rules('start', 'start','required|trim',
            array('required'      => 'Oops ! start is required')
        );

        $this->form_validation->set_rules('count', 'count','required|trim',
            array('required'      => 'Oops ! count is required')
        );

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('user_id')))$response['message'] =form_error('user_id');
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);
            $my_user_id = $this->input->post('my_user_id');

            $user_videos = $this->Post_model->user_likes_videos($user_id,$count,$start);

            $p_count = 0;
            $post_data = array();

            foreach ($user_videos as $post_data_value) {

                $post_data_userdata = get_row_data('users',array('user_id'=>$post_data_value['user_id']));
                $post_data_comments_count = $this->Common->get_total_rows('comments', array('post_id'=>$post_data_value['post_id']));
                $sound_where = array('sound_id'=>$post_data_value['sound_id']);
                $sound_data = get_row_data('sound',$sound_where);

                $likes_or_not_where = array('post_id'=>$post_data_value['post_id'],'user_id'=>$my_user_id);
                $video_likes_or_not = $this->Common->get_total_rows('likes', $likes_or_not_where);

                $post_data[$p_count]['post_id'] = $post_data_value['post_id'];
                $post_data[$p_count]['user_id'] = $post_data_value['user_id'];
                $post_data[$p_count]['full_name'] = $post_data_userdata['full_name'];
                $post_data[$p_count]['user_name'] = $post_data_userdata['user_name'];
                $post_data[$p_count]['user_profile'] = $post_data_userdata['user_profile'];
                $post_data[$p_count]['is_verify'] = $post_data_userdata['is_verify'];
                $post_data[$p_count]['is_trending'] = $post_data_value['is_trending'];
                $post_data[$p_count]['post_description'] = $post_data_value['post_description'];
                $post_data[$p_count]['post_hash_tag'] = $post_data_value['post_hash_tag'];
                $post_data[$p_count]['post_video'] = $post_data_value['post_video'];
                $post_data[$p_count]['post_image'] = $post_data_value['post_image'];
                $post_data[$p_count]['sound_id'] = $sound_data['sound_id'];
                $post_data[$p_count]['sound_title'] = $sound_data['sound_title'];
                $post_data[$p_count]['duration'] = $sound_data['duration'];
                $post_data[$p_count]['singer'] = $sound_data['singer'];
                $post_data[$p_count]['sound_image'] = $sound_data['sound_image'];
                $post_data[$p_count]['sound'] = $sound_data['sound'];
                $post_data[$p_count]['post_likes_count'] = $post_data_value['video_likes_count'];
                $post_data[$p_count]['post_comments_count'] = $post_data_comments_count;
                $post_data[$p_count]['post_view_count'] = $post_data_value['video_view_count'];
                $post_data[$p_count]['status'] = $post_data_value['status'];
                $post_data[$p_count]['created_date'] = $post_data_value['created_date'];
                $post_data[$p_count]['video_likes_or_not'] = $video_likes_or_not;
                $p_count++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'User likes video get successfully',
                'data' => $post_data,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function sound_video_post()
    {
        $this->form_validation->set_rules('sound_id', 'sound_id','required|trim',
            array('required'      => 'Oops ! user id required')
        );

        $this->form_validation->set_rules('start', 'start','required|trim',
            array('required'      => 'Oops ! start is required')
        );

        $this->form_validation->set_rules('count', 'count','required|trim',
            array('required'      => 'Oops ! count is required')
        );

        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run()== false)
        {
            $response['status']= FALSE;
            if(!empty(form_error('sound_id')))$response['message'] =form_error('sound_id');
            if(!empty(form_error('start')))$response['message'] =form_error('start');
            if(!empty(form_error('count')))$response['message'] =form_error('count');
        }
        else
        {
            extract($_POST);
            $my_user_id = $this->input->post('my_user_id');

            $sound_videos = $this->Post_model->sound_videos($sound_id,$count,$start);

            $sound_where = array('sound_id'=>$sound_id);
            $sound_data = get_row_data('sound',$sound_where);

            $p_count = 0;
            $post_data = array();

            $post_video_count = $this->Common->get_total_rows('post', $sound_where);
            $sound_data['post_video_count'] = $post_video_count;

            foreach ($sound_videos as $post_data_value) {

                $post_data_userdata = get_row_data('users',array('user_id'=>$post_data_value['user_id']));
                $post_data_comments_count = $this->Common->get_total_rows('comments', array('post_id'=>$post_data_value['post_id']));
                

                $likes_or_not_where = array('post_id'=>$post_data_value['post_id'],'user_id'=>$my_user_id);
                $video_likes_or_not = $this->Common->get_total_rows('likes', $likes_or_not_where);

                $post_data[$p_count]['post_id'] = $post_data_value['post_id'];
                $post_data[$p_count]['user_id'] = $post_data_value['user_id'];
                $post_data[$p_count]['full_name'] = $post_data_userdata['full_name'];
                $post_data[$p_count]['user_name'] = $post_data_userdata['user_name'];
                $post_data[$p_count]['user_profile'] = $post_data_userdata['user_profile'];
                $post_data[$p_count]['is_verify'] = $post_data_userdata['is_verify'];
                $post_data[$p_count]['is_trending'] = $post_data_value['is_trending'];
                $post_data[$p_count]['post_description'] = $post_data_value['post_description'];
                $post_data[$p_count]['post_hash_tag'] = $post_data_value['post_hash_tag'];
                $post_data[$p_count]['post_video'] = $post_data_value['post_video'];
                $post_data[$p_count]['post_image'] = $post_data_value['post_image'];
                $post_data[$p_count]['sound_id'] = $sound_data['sound_id'];
                $post_data[$p_count]['sound_title'] = $sound_data['sound_title'];
                $post_data[$p_count]['duration'] = $sound_data['duration'];
                $post_data[$p_count]['singer'] = $sound_data['singer'];
                $post_data[$p_count]['sound_image'] = $sound_data['sound_image'];
                $post_data[$p_count]['sound'] = $sound_data['sound'];
                $post_data[$p_count]['post_likes_count'] = $post_data_value['video_likes_count'];
                $post_data[$p_count]['post_comments_count'] = $post_data_comments_count;
                $post_data[$p_count]['post_view_count'] = $post_data_value['video_view_count'];
                $post_data[$p_count]['status'] = $post_data_value['status'];
                $post_data[$p_count]['created_date'] = $post_data_value['created_date'];
                $post_data[$p_count]['video_likes_or_not'] = $video_likes_or_not;
                $p_count++;
            }

            $response = array(
                'status' => TRUE,
                'message' => 'Sound wise videos get successfully',
                'sound_data' => $sound_data,
                'data' => $post_data
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function delete_comment_post()
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
            $this->form_validation->set_rules('comments_id', 'comments_id','required|trim',
            array('required'      => 'Oops ! comment required')
            );

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('comments_id')))$response['message'] =form_error('comments_id');
            }
            else
            {
                $user_id = $verify_data['userdata']['user_id'];
                $comments_id = $this->input->post('comments_id');

                $delete = $this->Common->deletedata('comments',array('comments_id'=>$comments_id));
                if($delete)
                {
                    $response = array(
                        'status' => TRUE,
                        'message' => 'Comment delete successfully'
                    );
                }
                else
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Comment delete failed'
                    );
                }
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function report_post()
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
            extract($_POST);
            $user_id = $this->input->post('user_id') ? $this->input->post('user_id') : '';
            $post_id = $this->input->post('post_id') ? $this->input->post('post_id') : '';
            $report_type = $this->input->post('report_type');
            $reason = $this->input->post('reason');
            $description = $this->input->post('description');
            $contact_info = $this->input->post('contact_info');
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
                    'message' => 'Your query submit successfully',
                );
            }
            else
            {
                $response = array(
                    'status' => FALSE,
                    'message' => 'Your query submit failed',
                );
            }
        }       

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function delete_post_post()
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
            $this->form_validation->set_rules('post_id', 'post_id', 'required|trim',
                array('required'      => 'Oops ! post id is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('post_id')))$response['message'] =form_error('post_id');
            }
            else
            {
                $user_id = $verify_data['userdata']['user_id'];
                extract($_POST);

                $data = array(
                    'status'=>0
                );

                $where = array('post_id'=>$post_id);
                $update = $this->Common->update('post', $where, $data);
                
                if($update)
                {
                    $response = array(
                        'status' => TRUE,
                        'message' => 'Post delete successfully'
                    );
                }
                else
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Post delete failed'
                    );
                }
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function favourite_sound_post()
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

            $request = trim(file_get_contents('php://input'));
            $data = json_decode($request);

            $this->db->select('*',FALSE);
            if(sizeof($data->sound_ids) > 0)
            {
                $this->db->where_in('sound_id',(array) $data->sound_ids);
            }

            $this->db->where('status',1);

            $sound_data = $this->db->get('sound')->result();

            $response = array(
                'status' => FALSE,
                'message' => 'Favourite Sound get successfully',
                'data' => $sound_data,
            );



        }

        $this->response($response, REST_Controller::HTTP_OK);

        // echo json_encode($responce);
    }
    // {"sound_ids":["1","2"]}

    private function implodeArrayKeys($array)
    {
        implode(', ', array_map(
            function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
            $array,
            array_keys($array)
        ));
    }
}
?>