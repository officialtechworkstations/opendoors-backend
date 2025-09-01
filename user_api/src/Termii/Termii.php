<?php

namespace user_api;

class Termii {
    public function sendSms($receiver, $message_body)
    {
        try {
            $curl = curl_init();
    
            global $rstate;
            require_once($_SERVER['DOCUMENT_ROOT'].'/include/reconfig.php');
    
            ltrim($receiver, '+');
    
            $termii = $rstate->query('SELECT * FROM `tbl_setting` WHERE `sms_type` = "Termii" LIMIT 1')->fetch_assoc();
            
            if (!$termii) {
                return false;
            }
    
            $data = [
                "api_key" => $termii['termii_api_key'], 
                "to" => $receiver,  
                "from" => "OpenDoors",
                "sms" => $message_body,
                "type" => "plain", 
                "channel" => "generic"
            ];
    
            $post_data = json_encode($data);
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => $termii['termii_base_url']."/api/sms/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            curl_close($curl);
    
            if($err) {
                return false;
            }
    
            $message_sent = json_decode($response, true);

            error_log(serialize($message_sent));
    
            if (in_array($message_sent['status'], [200, 201])) {
                return true;
            }
        } catch (\Throwable $th) {
            error_log($th->getMessage());
        }

        return false;
    }
}