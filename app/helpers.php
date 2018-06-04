<?php
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use App\Model\User as User;
use App\Model\Setting;
use App\Model\State;
use App\Model\Country;
use App\Model\VerifyUser;


if (! function_exists('getStateNameById')) {
 function getStateNameById($state_id)
    {
        $state = State::select('name')->where('id',$state_id)->first();
        return $state->name;
    }
}

if (! function_exists('getCountryNameById')) {
 function getCountryNameById($country_id)
    {
        $Country = Country::select('name')->where('id',$country_id)->first();
        return $Country->name;
    }
}

if (! function_exists('mailSend')) {
 function mailSend($data)
    {

        if($data['mail_type'] == 'contactForm'){
            try 
            { 
                Mail::send('emails.contact_form', ['data' => $data], function($message) use ($data)
                {
                    $message->from('oliver7415@googlemail.com', 'EATAPP');
                    $message->to( $data['adminEmail'] )->subject("Contact Form" );

                }); 
                //Session::flash('success', 'Change Password link successfully sent on your email.');
                //return redirect()->back()->withInput();
                return true;
            } catch (Exception $ex) {
                dd($ex);
                return false;            
            }

        }
        else if ($data['mail_type'] == 'forgot_password'){
            try 
            { 
                Mail::send('emails.forgot_password', ['data' => $data], function($message) use ($data)
                {
                    $message->from('oliver7415@googlemail.com', 'EATAPP');
                    $message->to( $data['email'] )->subject("Forgot Password" );

                }); 
                //Session::flash('success', 'Change Password link successfully sent on your email.');
                //return redirect()->back()->withInput();
                return true;
            } catch (Exception $ex) {
                dd($ex);
                return false;            
            }
        }
        else if ($data['mail_type'] == 'front_signup'){
            try 
            {

                $verifyUser = new VerifyUser();

                $verifyUser->user_id = $data['user_id'];
                $verifyUser->token = str_random(40);
                $verifyUser->save();
                $data['token'] = $verifyUser->token;
                $data['registration_url'] = url('/email-confirmation/verify').'/';
                Mail::send('emails.front_signup', ['data' => $data], function($message) use ($data)
                {
                    $message->from('oliver7415@googlemail.com', 'EATAPP');
                    $message->to( $data['email'] )->subject("Email Confirmation" );

                }); 
                Session::flash('success', 'Confirmation link successfully sent on your email.');
                //return redirect()->back()->withInput();
                return true;
            } catch (Exception $ex) {
                dd($ex);
                return false;            
            }
        }
        else if ($data['mail_type'] == 'app_signup'){
            try 
            {

                $verifyUser = new VerifyUser();

                $verifyUser->user_id = $data['user_id'];
                $verifyUser->token = str_random(40);
                $verifyUser->save();
                $data['token'] = $verifyUser->token;
                $data['registration_url'] = url('/email-confirmation/verify').'/';
                Mail::send('emails.app_signup', ['data' => $data], function($message) use ($data)
                {
                    $message->from('oliver7415@googlemail.com', 'EATAPP');
                    $message->to( $data['email'] )->subject("Email Confirmation From App" );

                }); 
                Session::flash('success', 'Confirmation link successfully sent on your email.');
                //return redirect()->back()->withInput();
                $message['success'] = 'Confirmation link successfully sent on your email.';
                return $message['success'];
            } catch (Exception $ex) {
                dd($ex);
                return false;            
            }
        }
        else {

            return true;
        }
    }
}

if (! function_exists('sendEmail')) {
 function sendEmail($user_id, $email_template_sulg)
    {
        $send = [
            "EmailBtn" => '<span class="f-left margin-r-5"> <a data-toggle="tooltip" class="btn btn-warning btn-xs" title="Send Email" href="'.route('subscribers.sendEmail', array('user_id'=>$user_id, 'email_template_sulg'=>$email_template_sulg)).'">Send</a></span>',
            ];
        return $send['EmailBtn'];
    }
}

if (! function_exists('getSettings')) {
 function getSettings()
    {
        return Setting::select('description','name')->pluck('description','name')->toArray();
    }
}

if (! function_exists('getLoggedUserInfo')) {
 function getLoggedUserInfo()
    {
        $user = \Session::get('AdminLoggedIn');

        return User::select('full_name','image','id')->where('id',$user['user_id'])->first();
    }
}

/*
** File Upload with intervation
*/
if ( ! function_exists('uploadwithresize'))
{
    function uploadwithresize($file,$path)
    { 
        $h=200;
        $w= 200;
        $fileName = time().rand(111111111,9999999999).'.'.$file->getClientOriginalExtension();
        $destinationPath    = 'public/uploads/'.$path.'/';
        // upload new image
        Image::make($file->getRealPath())
        // original
        ->save($destinationPath.$fileName)
        // thumbnail
        ->resize($w, $h)
        ->save($destinationPath.'thumb/'.$fileName)
        ->destroy();
        return $fileName;
    }
}

/*
** File Upload
*/
if ( ! function_exists('upload'))
{
    function upload($fileName,$path)
    {
            $file = $fileName;
            $destinationPath = 'public/uploads/'.$path;
            $extension = $file->getClientOriginalExtension();
            $fileName = rand(11111,99999).'.'.$extension;
            $file->move($destinationPath, $fileName);
            return $fileName;
    }
}

// remove file from folder
if ( ! function_exists('unlinkfile'))
{
    function unlinkfile($path,$file_name)
    {
        $file1    = 'public/uploads/'.$path.'/'.$file_name;
        $file2    = 'public/uploads/'.$path.'/thumb/'.$file_name;
       File::delete($file1, $file2);
    }
}

/*
 * * Button With Html
 */
if (!function_exists('buttonHtml')) {

    function buttonHtml($key, $link) {
        $array = [
            "edit" => "<span class='f-left margin-r-5'><a data-toggle='tooltip'  class='btn btn-primary btn-xs' title='Edit' href='" . $link . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></span>",
            "Active" => '<span class="f-left margin-r-5"> <a data-toggle="tooltip" class="btn btn-success btn-xs" title="Active" href="' . $link . '"><i class="fa fa-check" aria-hidden="true"></i></a></span>',
            "InActive" => '<span class="f-left margin-r-5"> <a data-toggle="tooltip" class="btn btn-warning btn-xs" title="InActive" href="' . $link . '"><i class="fa fa-times" aria-hidden="true"></i></a></span>',

            "delete" => '<form method="POST" action="' . $link . '" accept-charset="UTF-8" style="display:inline"><input name="_method" value="POST" type="hidden">
' . csrf_field() . '<span><button data-toggle="tooltip" title="Delete" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" aria-hidden="true"></i></button></span></form>',
            "view" => '<span class="f-left margin-r-5"><a data-toggle="tooltip"  class="btn btn-info btn-xs" title="View" href="' . $link . '"><i class="fa fa-eye" aria-hidden="true"></i></a></span>'
        ];

        if (isset($array[$key])) {
            return $array[$key];
        }
        return '';
    }

}

/*
 * * Button With Html
 */
if (!function_exists('getButtons')) {

    function getButtons($array = []) {
        $html = '';
        foreach($array as $arr)
        {
            $html  .= buttonHtml($arr['key'],$arr['link']);
        }
        return $html;
      
    }

}

/*
 * * Button With Html
 */
if (!function_exists('getStatus')) {

    function getStatus($current_status,$id) {
       $html = '';
      switch ($current_status) {
          case '1':
               $html =  '<span class="f-left margin-r-5" id = "status_'.$id.'"><a data-toggle="tooltip"  class="btn btn-success btn-xs" title="Active" onClick="changeStatus('.$id.')" >Active</a></span>';
              break;
               case '0':
               $html =  '<span class="f-left margin-r-5" id = "status_'.$id.'"><a data-toggle="tooltip"  class="btn btn-danger btn-xs" title="InActive" onClick="changeStatus('.$id.')" >InActive</a></span>';
              break;
          
          default:
            
              break;
      }

      return $html;
      
    }

}


if (! function_exists('getLoggedCompanyInfo')) {
 function getLoggedCompanyInfo()
    {
        $user = \Session::get('CompanyLoggedIn');

        return User::where('id',$user['user_id'])->first();
    }
}




if (! function_exists('setcookiefunction')) {
 function setcookiefunction($cookie_name,$cookie_value,$time)
    {
        setcookie($cookie_name,$cookie_value);
    }
}

