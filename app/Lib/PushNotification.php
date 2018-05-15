<?php
namespace App\Lib;
class PushNotification  {

    public $live = false;
    
   
    public static  function sendAlertMsg($device_tokens, $msg1, $dictionary = '', $type = '') {
    
        $passphrase = 'intel123';
        $message = $msg1;

        $ctx = stream_context_create();
        $pem=base_path("public/Shooti_APN_Dev_Certificates.pem");
        if(!file_exists($pem)){
//            ErrorLog::log("Error Push Notificaton",$pem);
        }
        stream_context_set_option($ctx, 'ssl', 'local_cert',$pem);
        //stream_context_set_option($ctx, 'ssl', 'local_cert', APPPATH.'services/YachtMe_APN_Dis_Certificates.pem');
        //$apns_url = 'gateway.push.apple.com';
        $apns_url = 'gateway.sandbox.push.apple.com';

        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        $fp = stream_socket_client('ssl://' . $apns_url . ':2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
      


        if (!$fp){
             exit("Failed to connect: $err $errstr" . PHP_EOL);
        }
//            ErrorLog::log("Failed to connect",$errstr);
//            exit("Failed to connect: $err $errstr" . PHP_EOL);
        foreach ($device_tokens as $device_token) {



            $body['aps'] = array(
                'alert' => $message,
                'dictionary' => $dictionary,
                'type' => $type,
                'sound' => 'default',
                'badge' => 1//self::updateBedge($device_token),
                
            );
// ErrorLog::log("Devices",$body['aps']);
            // Encode the payload as JSON
            $payload = json_encode($body);
           
            $device_token = str_replace("", "", $device_token);
            $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;

            $result = fwrite($fp, $msg, strlen($msg));
            
//            ErrorLog::log("Res Notif-",$result);
            
         
          
        }

       
        fclose($fp);
    }

    public static  function sendGcmNotify($reg_id, $message, $dictionary = '', $type = '') {
        $ttl = 86400;
        $randomNum = rand(10, 100);


        if (!is_array($reg_id)) {
 
            $fields = array(
                'registration_ids' => array($reg_id),
                'data' => array("message" => $message, 'dictionary' => $dictionary, 'type' => $type,"title"=>"Bazzar" ),
                 'notification' => array("message" => $message,   'type' => $type,"title"=>"Bazzar","body"=> $message) ,
            );
        } else {
 
            $fields = array(
                'registration_ids' => $reg_id,
                'notification' => array("message" => $message,   'type' => $type,"title"=>"Bazzar","body"=> $message,"click_action"=>"FCM_PLUGIN_ACTIVITY",) ,
                'data' => array("message" => $message,   'type' => $type,"title"=>"Bazzar","body"=> $message,"content_available" => 1,"force-start"=> 1,"dic"=>$dictionary) ,
            
                'delay_while_idle' => false,
                'time_to_live' => $ttl,
                
 
                'collapse_key' => "" . $randomNum . ""
            );
        }
 

        $headers = array(
            'Authorization: key=AIzaSyBaPhP6Vp9Xq9lLgLbFdCHLM5fs2IRlXk4',
            'Content-Type: application/json'
        );
 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
//        echo $result;die;
        if ($result === FALSE) {
//            die('Problem occurred: ' . curl_error($ch));
        }

        curl_close($ch);
//        echo $result;
    }
    function updateBedge($deviceId){
      return  UserDevices::saveDeviceBedge($deviceId,UserDevices::getUserBedge($deviceId)+1);
    }
    function updateBedgeSer($rs){
        $deviceId=$rs['device_token'];
        $userid=$rs['userid'];
        UserDevices::saveDeviceBedge($deviceId,0);
        return array("status"=>"true","msg"=>"success");
    }
   public static function Notify($users,$message,$type="",$dic=[]){
       
       $UserDevicesIOS      =[];
       $UserDevicesAndroid  =[];
       
        foreach($users as $userid){
           $Setting= \App\Models\User::find($userid)->push_notification;
//         
           if($Setting==0){
               continue;
           }
                    
          $deviceinfo= \App\Models\UserDevice::where(["userid"=>$userid])->get()->toArray();
$deviceinfo=\DB::table("user_devices")->where(["userid"=>$userid])->get()->toArray();
       
         //   $deviceinfo= UserDevices::getUser("user_id='$userid' and device_token!=''","device_token");
                foreach($deviceinfo as $deviceToken){
//                    if($deviceToken["device_type"]=="iOS")
//                        $UserDevicesIOS[]=$deviceToken["device_id"];
//                    if($deviceToken["device_type"]=="Android")
                        $UserDevicesAndroid[]= $deviceToken->device_id;
                }
        }
      //print_r($UserDevicesAndroid);die;
       //PushNotification::sendAlertMsg($UserDevicesIOS, $message,$dic,$type);
       PushNotification::sendGcmNotify($UserDevicesAndroid, $message,$dic,$type);
        
    }
   
}

?>