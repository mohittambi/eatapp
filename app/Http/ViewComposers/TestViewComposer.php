<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Model\FrontPage;
use App\Model\Setting;
use Request;

class TestViewComposer {

    public function compose(View $view) {
    	if(Request::url())
        {   $url = Request::url();
            $slugs = explode ("/", $url);
            $latestslug = $slugs [(count ($slugs) - 1)];
            //dd($latestslug);
        }
    	$data = FrontPage::where('slug',$latestslug)->first();

        $settings = Setting::where('status','1')->get();
            foreach($settings as $setting => $value){
                $this->settingValue[$value->slug] = $value->description;
            }

        $view->with(['ComposerServiceProvider'=> $data,'settingValue'=>$this->settingValue]);
    }
}