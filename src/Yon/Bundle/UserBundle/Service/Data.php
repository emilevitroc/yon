<?php 

namespace Yon\Bundle\UserBundle\Service;

class Data {
    
    public function __construct() {

    }

    // Curl 
    public function curlPost($_zUrl, $post_data = null, $_custom_header = null) {

        
        if ($_zUrl && $post_data) {
            $fields_string = '';
            
            $header = array (
                'Accept-Language: fr',
                'Content-Type: application/json',
            );
            
            if($_custom_header){
                $header = array_merge($header, $_custom_header);
            }
//            echo $_zUrl;
//            var_dump($header);
//            die;
            foreach($post_data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string,'&');
            
            //echo $fields_string;
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $_zUrl);
            curl_setopt($ch,CURLOPT_POST, count($post_data));
//            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

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
    
    public function curlPatch($_zUrl, $post_data = null, $_custom_header = null) {

        if ($_zUrl && $post_data) {
            
            $headers = array(
                'Accept-Language: fr',
                'Content-Type: application/json'
            );
            if($_custom_header){
                $headers = array_merge($headers, $_custom_header);
            }
            
//            var_dump($_zUrl);
//            var_dump($headers);
//            var_dump($post_data);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $_zUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            
            $response = curl_exec($curl);
            curl_close($curl);
            
            return $response;
        } else {
            return null;
        }
    }
    
    public function curlDelete($_zUrl, $_custom_header = null) {

        if ($_zUrl) {
            
            $headers = array(
                'Accept-Language: fr',
                'Content-Type: application/json'
            );
            if($_custom_header){
                $headers = array_merge($headers, $_custom_header);
            }
            
//            var_dump($_zUrl);
//            var_dump($headers);
//            var_dump($post_data);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $_zUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
//            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            
            $response = curl_exec($curl);
            curl_close($curl);
            
            return $response;
        } else {
            return null;
        }
    }
    
}
?>