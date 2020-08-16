<?php

function admin_profile()
{
    $ci = & get_instance();
    $ci->load->model('Common');
    $where = array('admin_id'=>1);
    $query = $ci->Common-> get_data_row('tbl_admin', $where, $field = '*','admin_id');
    return $query['admin_profile'];
}

function get_row_data($table,$where)
{
    $ci = & get_instance();
    $ci->load->model('Common');
    $data = $ci->Common->select_where_result($table, $where);
    return $data;
}

function send_push($fcmtoken,$message,$plateform)
{
    if($plateform == 0)
    {
        $customData =  array("message" =>$message);
        
        $url = 'https://fcm.googleapis.com/fcm/send';

        $api_key = 'AAAAN9Gh7Tg:APA91bHRpu-4btM8P6uk9XXHrkedHYgGdrwma7h4bNtGIHeyiJJCn50LuLFpb_ILtlfHuQv4eGlRVNUfiT6QwR3V38oADNn6uEjeMUFsTHlUQFlOUijqnCzqTGJFZodAmsv6CbHKGDZB';

        $fields = array (
            'registration_ids' => array (
                $fcmtoken
            ),
            'data' => $customData
        );

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // print_r(json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        // print_r($result);
        return $result;
    }
    else
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $api_key = 'AAAArF0j9OA:APA91bF-kVKEENI0DoTtWocxcmhPjYrTDLjkrrch3aWrpWjqGKuwQ234deDRCL8ds_yj-9uvv8NdQYImiALpkFNi4vWPKbAjbQl8H599eUE3v03sLAehMwtSY2RH4TSuqECS2URHuy4e';

        $title = $message;

        $msg = array ( 'title' => 'this is title', 'body' => 'this is a description');

        $message = array(
            "message" => $title,
            "data" => $message,
        );

        $data = array('registration_ids' => array($fcmtoken));
        $data['data'] = $message;
        $data['notification'] = $msg;
        $data['notification']['sound'] = "default";

        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        //echo json_encode($data);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        // print_r($result);
        return $result;
    }

}


function verify_request()
{
    $ci = & get_instance();
    $ci->load->database();

    // Get all the headers
    $headers = $ci->input->request_headers();

    // Extract the token
    if((!isset($headers['Authorization'])))
    {
        $status = 401;
        $response = array('status' => $status, 'errors' => 'Unauthorized Access!');
         return $response;
        exit();
    }
    else
    {
        $token = $headers['Authorization'];
        
    }

    // Use try-catch
    // JWT library throws exception if the token is not valid
    // try {
        // Validate the token
        // Successfull validation will return the decoded user data else returns false
        $data = AUTHORIZATION::validateToken($token);
        if ($data === false) {
           $status = 401;
            $response = array('status' => $status, 'errors' => 'Unauthorized Access!');
            return $response;
            // return $ci->response($response, 401);
            exit();
        } else {

            $ci = &get_instance();
            $ci->load->model('Common');
            $where = array('token'=>$token);
            $query_count = $ci->Common->get_total_rows('users', $where);            

            if($query_count != 1)
            {
                $status = 401;
                $response = array('status' => $status, 'errors' => 'Unauthorized Access!');
                return $response;
                // return $ci->response($response, 401);
                exit();
            }
            else
            {
                $ci = &get_instance();
                $ci->load->model('Common');
                $where = array('token'=>$token);
                $query = $ci->Common-> get_data_row('users', $where, $field = '*','user_id');

                $status = 200;
                $response = array('status' => $status, 'errors' => 'Authorized Access!', 'userdata'=>$query);
                return $response;
                // return $ci->response($response, 200);
                exit();
            }

            // return $data;
        }
}

    //google authentication

    function get_qr_code($email,$secret)
    {
        require_once (APPPATH.'/third_party/GoogleAuthenticator.php');

        $ga = new PHPGangsta_GoogleAuthenticator();

        return $ga->getQRCodeGoogleUrl($email, $secret, 'Joy Wallet', array('width'=>300,'height'=>300));
    }

    function verify_code_google($secret, $oneCode)
    {
        require_once (APPPATH.'/third_party/GoogleAuthenticator.php');

        $ga = new PHPGangsta_GoogleAuthenticator();

        return $ga->verifyCode($secret, $oneCode, 0);
    }

    function create_code_google()
    {
        require_once (APPPATH.'/third_party/GoogleAuthenticator.php');

        $ga = new PHPGangsta_GoogleAuthenticator();

        return $ga->createSecret();
    }

    function set_google_secret()
    {
        $ci = & get_instance();
        $admin_id = $ci->session->userdata('admin_id');
        if(!empty($admin_id))
        {
            $twofa_secret = create_code_google();
            $query ="UPDATE tbl_admin SET twofa_secret='".$twofa_secret."' WHERE admin_id='".$admin_id."'";
            $result = $ci->db->query($query);
            return $twofa_secret;
        }
    }
    //end google auhentication

?>