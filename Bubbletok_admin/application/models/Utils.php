<?php

class Utils extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    //solve for queryresult->row
     public function solveNulls($object)
    {
       // var_dump($object);
        foreach ($object as $key => $value) {

                    if(is_array($value))
                    $this->solveNullsForArray($value);
                    else if( is_object($value))
                    $this->solveNulls($value);
                    else if($value == null) {
                        $object->$key = "";
                    }

            }
            return $object;
    }
    public function solveNullsForArray($object)
    {
        for ($i = 0; $i < count($object); $i++) {
            $object[$i]= $this->solveNulls($object[$i]);
        }

            return $object;
    }
      public function returnObject($code , $message)
    {
        return [
                KEY_RESULT=>$code,
                KEY_RES_MESSAGE=>$message];
    }

    public function generateRandomString($length = 10) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+<>?:{};[],./";
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}