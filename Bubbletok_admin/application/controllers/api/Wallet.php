<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
require APPPATH . 'libraries/Format.php';


class Wallet extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('form_validation');
        $this->load->model('Common');
        $this->load->model('Post_model');
        $this->load->model('User_model');
        $this->load->model('Wallet_model');
        $this->load->model('Utils');
    }

    public function coin_rate_get()
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

            $data = $this->Common->select_where_result('coin_rate', array('coin_rate_id'=>1), $field = '*', 'coin_rate_id');

            $response = array(
                'status' => TRUE,
                'message' => 'Coin rate get successfully',
                'data' => $data,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function rewarding_action_get()
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

            $data = $this->Common->selectall_where_result('rewarding_action', array('status'=>1), $field = '*', 'rewarding_action_id');

            $response = array(
                'status' => TRUE,
                'message' => 'Rewarding action details get successfully',
                'data' => $data,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function add_coin_post()
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
            $this->form_validation->set_rules('rewarding_action_id', 'rewarding_action_id', 'required|trim',
                array('required'      => 'Oops ! rewarding action id is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('rewarding_action_id')))$response['message'] =form_error('rewarding_action_id');
            }
            else
            {
                extract($_POST);

                $user_id = $verify_data['userdata']['user_id'];

                $where = array('rewarding_action_id'=>$rewarding_action_id);
                $coin_data = $this->Common->select_where_result('rewarding_action', $where);
                $coin = $coin_data['coin'];

                if($rewarding_action_id == 1)
                {
                    $count_update = $this->db->query('UPDATE users SET spen_in_app=spen_in_app+'.$coin.' WHERE user_id ='.$user_id);
                }
                elseif($rewarding_action_id == 2)
                {
                   $count_update = $this->db->query('UPDATE users SET check_in=check_in+'.$coin.' WHERE user_id ='.$user_id);
                }
                elseif($rewarding_action_id == 3)
                {
                   $count_update = $this->db->query('UPDATE users SET upload_video=upload_video+'.$coin.' WHERE user_id ='.$user_id);
                }
                elseif($rewarding_action_id == 4)
                {
                   $count_update = $this->db->query('UPDATE users SET from_fans=from_fans+'.$coin.' WHERE user_id ='.$user_id);
                }
                elseif($rewarding_action_id == 5)
                {
                   $count_update = $this->db->query('UPDATE users SET purchased=purchased+'.$coin.' WHERE user_id ='.$user_id);
                }

                $wallet_update = $this->db->query('UPDATE users SET my_wallet=my_wallet+'.$coin.' WHERE user_id ='.$user_id);

                $total_received = $this->db->query('UPDATE users SET total_received=total_received+'.$coin.' WHERE user_id ='.$user_id);
                

                $response = array(
                    'status' => TRUE,
                    'message' => 'Coin add successfully'
                );
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function send_coin_post()
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

            $this->form_validation->set_rules('to_user_id', 'to_user_id', 'required|trim',
                array('required'      => 'Oops ! two id is required.'
            ));

            $this->form_validation->set_rules('coin', 'coin', 'required|trim',
                array('required'      => 'Oops ! coin is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('to_user_id')))$response['message'] =form_error('to_user_id');
                if(!empty(form_error('coin')))$response['message'] =form_error('coin');
            }
            else
            {
                extract($_POST);

                $user_id = $verify_data['userdata']['user_id'];
                $full_name = $verify_data['userdata']['full_name'];

                $where = array('user_id'=>$user_id);
                $data = $this->Common->get_data_row('users', $where, $field = 'my_wallet,spen_in_app,check_in,upload_video,from_fans,purchased', 'status');

                $wallet = $data['my_wallet'];

                if($wallet >= $coin)
                {
                    // $count_update = $this->db->query('UPDATE users SET from_fans=from_fans-'.$coin.' WHERE user_id ='.$user_id);
                    $count_update = $this->db->query('UPDATE users SET my_wallet=my_wallet-'.$coin.' WHERE user_id ='.$user_id);

                    $total_send = $this->db->query('UPDATE users SET total_send=total_send+'.$coin.' WHERE user_id ='.$user_id);

                    $wallet_update = $this->db->query('UPDATE users SET from_fans=from_fans+'.$coin.' WHERE user_id ='.$to_user_id);
                    $wallet_update = $this->db->query('UPDATE users SET my_wallet=my_wallet+'.$coin.' WHERE user_id ='.$to_user_id);

                    $update = $this->db->query('UPDATE users SET total_received=total_received+'.$coin.' WHERE user_id ='.$to_user_id);

                    $noti_user_id = $to_user_id;
                    $noti_data = get_row_data('users',array('user_id'=>$noti_user_id));
                    $platform = $noti_data['platform'];
                    $device_token = $noti_data['device_token'];
                    $message = $full_name.' sent you '.$coin.' bubbles';


                    $notificationdata = array(
                        'sender_user_id'=>$user_id,
                        'received_user_id'=>$noti_user_id,
                        'notification_type'=>'send_coin',
                        'message'=>$message,
                        'created_date'=>date('Y-m-d H:i:s')
                    );

                    $insert = $this->Common->insert('notification', $notificationdata);

                    send_push($device_token,$message,$platform);
                    
                    $response = array(
                        'status' => TRUE,
                        'message' => 'Coin send successfully'
                    );
                }
                else
                {
                    $response = array(
                        'status' => False,
                        'message' => 'You have insufficient wallet balance'
                    );
                }
                

                
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function purchase_coin_post()
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

            $this->form_validation->set_rules('coin', 'coin', 'required|trim',
                array('required'      => 'Oops ! coin is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('coin')))$response['message'] =form_error('coin');
            }
            else
            {
                extract($_POST);

                $user_id = $verify_data['userdata']['user_id'];
               
                $count_update = $this->db->query('UPDATE users SET purchased=purchased+'.$coin.' WHERE user_id ='.$user_id);

                $wallet_update = $this->db->query('UPDATE users SET my_wallet=my_wallet+'.$coin.' WHERE user_id ='.$user_id);

                $update = $this->db->query('UPDATE users SET total_received=total_received+'.$coin.' WHERE user_id ='.$user_id);

                $response = array(
                    'status' => TRUE,
                    'message' => 'Coin purchased successfully'
                );
            }
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function my_wallet_coin_get()
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
            $where = array('user_id'=>$user_id);
            $data = $this->Common->get_data_row('users', $where, $field = 'total_received,total_send,my_wallet,spen_in_app,check_in,upload_video,from_fans,purchased', 'status');

            $response = array(
                'status' => TRUE,
                'message' => 'My wallet get successfully',
                'data' => $data,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function coin_plan_get()
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

            $data = $this->Common->selectall_where_result('coin_plan', array('status'=>1));

            $response = array(
                'status' => TRUE,
                'message' => 'Coin plan get successfully',
                'data' => $data,
            );
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }

    public function redeem_request_post()
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

            $this->form_validation->set_rules('amount', 'amount', 'required|trim',
                array('required'      => 'Oops ! amount is required.'
            ));

            $this->form_validation->set_rules('redeem_request_type', 'redeem_request_type', 'required|trim',
                array('required'      => 'Oops ! redeem request type is required.'
            ));

            $this->form_validation->set_rules('account', 'account', 'required|trim',
                array('required'      => 'Oops ! account is required.'
            ));

            $this->form_validation->set_error_delimiters('', '');
            if($this->form_validation->run()== false)
            {
                $response['status']= FALSE;
                if(!empty(form_error('amount')))$response['message'] =form_error('amount');
                if(!empty(form_error('redeem_request_type')))$response['message'] =form_error('redeem_request_type');
                if(!empty(form_error('account')))$response['message'] =form_error('account');
            }
            else
            {

                $user_id = $verify_data['userdata']['user_id'];
                extract($_POST);
                $created_date = date('Y-m-d H:i:s');

                $data = array('redeem_request_type'=>$redeem_request_type,'account'=>$account,'amount'=>$amount,'user_id'=>$user_id,'created_date'=>$created_date);
                $insert = $this->Common->insert('redeem_request', $data);

                $update_data = array(
                    'total_received'=>0,
                    'total_send'=>0,
                    'my_wallet'=>0,
                    'spen_in_app'=>0,
                    'check_in'=>0,
                    'upload_video'=>0,
                    'from_fans'=>0,
                    'purchased'=>0
                );

                $update =  $this->Common->update('users', array('user_id'=>$user_id), $update_data);

                if($insert)
                {
                    $response = array(
                        'status' => TRUE,
                        'message' => 'Redeem request successfully',
                    );
                }
                else
                {
                    $response = array(
                        'status' => FALSE,
                        'message' => 'Redeem request failed',
                    );
                }
            }       
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }
  
}
?>