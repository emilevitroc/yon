<?php 

namespace Yon\Bundle\UserBundle\Service;

class Data {
    
    public function __construct() {

    }

    // Curl 
    public function curlPost($_zUrl, $post_data = null) {

        if ($_zUrl && $post_data) {
            $fields_string = '';
            foreach($post_data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string,'& ');
            
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $_zUrl);
            curl_setopt($ch,CURLOPT_POST, count($post_data));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

            //execute post
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
            
            return $result;
        } else {
            return null;
        }
    }
    
    public function curlGet($_zUrl, $post_data = null) {

        if ($_zUrl && $post_data) {
            $fields_string = '';
            foreach($post_data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string,'& ');
            
            $ch = curl_init();
            $FinalUrl = $_zUrl;
            if($fields_string != ''){
               $FinalUrl = $_zUrl.'?'.$fields_string; 
            }

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $FinalUrl);
            
            //execute post
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
            
            return $result;
        } else {
            return null;
        }
    }
    
}
?>