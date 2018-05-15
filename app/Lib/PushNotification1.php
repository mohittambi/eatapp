    public function sendNotification($user_ids_array,$msg)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $all_devices = UserDevice::select('device_type','device_id')->whereIn('user_id',$user_ids_array)->get();
        $iphone_array = [];
        $android_array = [];
        $server_key = 'AIzaSyCzZ4QXkM484MKxp4eoNCchwVd0zIUBSyA';
        if(count($all_devices)>0)
        {
            foreach($all_devices as $device)
            {
                if($device->device_type == 'ANDROID')
                {
                    $android_array[] = $device->device_id;
                }
                if($device->device_type == 'IPHONE')
                {
                    $iphone_array[] = $device->device_id;
                }
            }
        }
        
        if(count($iphone_array)>0)
        {
           
             $fields = array
            (
                'priority'             => "high",
                'notification'         => array( "title"=>"Brew-Restro", "body" =>$msg)
                //'data'                 => $type,
            ); 
            
           
                
             if(count($iphone_array)>1)
            {
                $fields['registration_ids'] = $iphone_array;
            }
            else
            {
                $fields['to'] = $iphone_array[0];
            }
            $headers = array(
                            'Content-Type:application/json',
                            'Authorization:key='.$server_key
                        );  
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            



        }
        if(count($android_array)>0)
        {
          
           
            $fields = array
            (
                'priority'             => "high",
                'notification'         => array( "title"=>"Brew-Restro", "body" =>$msg),
                'data' => array("title" => 'Brew-Restro', 'type' => 'nofic', 'notify_msg' => $msg, 'msg' => $msg)
            );
            if(count($android_array)>1)
            {
                $fields['registration_ids'] = $android_array;
            }
            else
            {
                $fields['to'] = $android_array[0];
            }
            $headers = array(
                            'Content-Type:application/json',
                            'Authorization:key='.$server_key
                        );  
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }
      
    }