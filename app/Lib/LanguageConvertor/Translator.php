<?php
namespace App\Lib\LanguageConvertor;
use App\Lib\LanguageConvertor\BingTranslator as BingTranslator;

class Translator{
    public static $default               = 1;  //English
    public $live_translation             = true;  //if word not found live translation work
    public static $words                 = [];  //word to be translate in lang
    public $lang_file_exist              = false;
    public static $to_be_translate_words = [];  //store words for translation
    public static $file_path             = ""; 
    public static $lang_folder           = "langauge"; 
    public static $user_language         = []; 
    public static $user_language_code    = ""; 
    public static $languages = [
                                1=>["name"=>"English","code"=>"en","id"=>"1"],
                                2=>["name"=>"Italian","code"=>"it","id"=>"2"],
                            //    2=>["name"=>"Hebrew","code"=>"he","id"=>"2"]
                                ]; 
    
    
    function __construct($userlang,$langaues=[],$folder="langauge"){

        if(count($langaues)>0){
            self::setLanguages($langaues);
        }
         
        if($userlang!=""){
            $lang          = self::languages($userlang);
        }
        else {
            $lang          = self::languages(self::$default);
        }
        
        self::$user_language        = $lang;
        self::$user_language_code   = self::$user_language['code'];
        self::setLanguageModule($folder);
        $this->checkFile(self::$user_language_code);
        
    }


    public static function setLanguageModule($folder){
        self::$file_path            = dirname(__FILE__);
        self::$lang_folder          = self::$file_path."/$folder/";

    }


    public static function setLanguages($langaues){
      
        foreach($langaues  as $k=>$lang){
            $lang=(array)$lang;
 
            $all[$lang['id']]=$lang;
        }
         
        self::$languages=$all;
    }
    

    public static function javascriptEnableWidget($url="ajaxconvert.php"){
    
        $words=json_encode(self::$words);
        $ajax="   $.ajax({
                    type: 'GET',
                    url: '$url',
                    data: {word:v},
                    success: function(dta){ fnl= dta;translateWords[v]=dta; },
                    async:false
                  });";
      
       return  "<script>"
                . " var translateWords=$words; \n  "
                . "  function _t(v){ \n var fnl='';\n  if(translateWords[v]==undefined){ \n  $ajax }\n else { \n fnl= translateWords[v]; } \n"
               . "return fnl;"
               . "}</script>";
    }


    public static function translatorWidget($url="change_language.php"){
       $languages       = self::$languages;
       $user_language   = self::$user_language;
       $select= "<select onchange='window.location=\"$url/\"+$(this).val()' >";
                    for($i=1;$i<=count($languages);$i++){ 
                        $lan        =$languages[$i];
                        $selected   =$lan['id']==$user_language['id']?'selected':"";
                        $select.= " <option $selected value='{$lan['id']}'>{$lan['name']}</option>";
                    }
       return $select.="</select>";
    }
    

    public static function allWords(){
        return self::$words;
    }
    

    public static function languages($code=''){
        $langs  = self::$languages;
       
        if($code==""){
            return $langs;
        }
        else
            return $langs[$code];
    }
    

    function checkFile($lang){
         $dir = self::$file_path;
         if(file_exists(self::$lang_folder."$lang/all_lang.php")){
            $this->lang_file_exist =true;
            self::$words =  self::getLangFile($lang);
        }
        else{
            if($this->createLangFile($lang)){
                $this->lang_file_exist =true;
                 self::$words =  self::getLangFile($lang);
            }
            else{
                die("translation file creation failed");
            }
            
        }
    }
    

    function createLangFile($lang){
           $data="<?php return [] ?>";
           
           if (!@mkdir(self::$lang_folder."$lang", 0777, true)) {
            die('Translation folder failed to create folders...');
           }
           return file_put_contents(self::$lang_folder."$lang/all_lang.php",$data);
    }
    

    public static function putInLangFile($words){
           $data="<?php return ". var_export($words,true)." ?>";
          
           return file_put_contents(self::$lang_folder.self::$user_language_code."/all_lang.php",$data);
    }
    

    public static function getLangFile($lang){
          
           return include self::$lang_folder."$lang/all_lang.php";
    }
    

    function translateApiArray($words,$from,$to){
        
    }
    
    
    public static function hasWordValue($val){
     
        if(in_array($val,self::$words)){
            return self::$words[$val];
        }
        return false;
    }


    public static function getWordValue($val){
        if($val==""){
            return "";
        }
      
        if(isset(self::$words[$val])){
            return self::$words[$val];
        }
        else{
            return self::liveConvertor($val);
        }
        return false;
    }


    public static  function liveConvertor($to_be_translate_words){
       
        if($to_be_translate_words!=""){
            $def_code=(self::languages(self::$default)['code']);
            if(self::$user_language_code==$def_code){
                self::$words[$to_be_translate_words]=$to_be_translate_words; 
            }
            else{
               $BingTranslator = new BingTranslator('AppID', 'secret');

               self::$words[$to_be_translate_words] = @$BingTranslator->getTranslation($def_code, self::$user_language_code, $to_be_translate_words);
            }
            self::putInLangFile(self::$words);
            return self::$words[$to_be_translate_words] ;
        }   
    }
    
    public static  function languageEditorWidget($id,$url="updatelang.php"){
        // $langs= self::$languages;
        $langDetail = self::languages($id);
        $words      = self::getLangFile($langDetail['code']);
        $table      = "<table border='1'><tr><th>Name</th><th>Value</th></tr>";
        foreach($words as  $k=>$word){
            $k = htmlspecialchars($k,ENT_QUOTES);
            $table.="<tr><td>$k</td><td onclick='showEditor($id,\"$k\",\"$url\",this);'>$word</td></tr>";
        }
        $table.="</table>";
        $table.='<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
                <script>
                    function showEditor(id,name,url,e){
                        if(v=prompt("Change value",$(e).html())){
                            $.get(url,{id:id,name:name,value:v},function(data){
                                if(data!="1"){
                                    alert("Try again");
                                }
                                else{
                                    $(e).html(v);
                                }
                            })
                        }
                    }
                    </script>';
        return $table;
    }


    public static function AllLangWidget($url="vieweditor.php"){
        $langs= self::$languages;
        
        $table="<table><tr><th>Language</th><th>Code</th><th>View/Edit</th></tr>";
       
        foreach($langs as $k=>$lang){
            
            $table.="<tr><td>{$lang['name']}</td><td>{$lang['code']}</td><td><a href='$url?id={$lang['id']}' >Edit/View</a></td></tr>";
        }
        $table.="</table>";
        
        return $table;
    }


    public static function updateLanguageValue($id,$name,$val){
      
        $langDetail      = self::languages($id);
        $words           = self::getLangFile($langDetail['code']);
        $words[$name]    = $val;
        $data            = "<?php return ". var_export($words,true)." ?>";
        file_put_contents(self::$lang_folder.$langDetail['code']."/all_lang.php",$data);
        return 1;
        
    }


    public static function languageFields($name="",$value="",$options=[]){
        $defaultOption   = ['type'=>"text", "name"=>"translation_".$name."_", "tag"=>"input"];
        $options         = array_merge($defaultOption,$options);
        $langs           = self::languages();
       
        $input="";
        $value="";
//dd($options);
        foreach($langs as $lang)
        {
            if($lang['id']!=self::$default){
                $valueDb="";

                if($options["table"]!="" && $options["pk"]!=""){
                    $valueDb = \DB::table("translation")->where("fk",$options["pk"])
                        ->where("table_name",$options["table"])
                        ->where("column_name",$name)
                        ->where("locale",$lang['code'])->first();

                    //    if($name!=""){
                    //        $words=self::getLangFile($lang['code']);
                         
                    //        if(isset($words[$name])){
                    //            $value=$words[$name];
                    //        }
                    //        else
                    //            $value="";
                    //    }
                }
            if($valueDb)
                $value=$valueDb->value;
            else
                $value='';
                
            $nameinplaceholder = ucfirst($name);
            
            if($options['tag']=="input"){
                $input.= "<label class='form-control-label'>{$nameinplaceholder} ({$lang['name']})</label><{$options['tag']} type='{$options['type']}' class='form-control' value='$value' placeholder='{$nameinplaceholder} in {$lang['name']}' name='{$options['name']}{$lang['code']}' />";
            }
            else if($options['tag']!="input"){
                $input.="<label class='form-control-label'>{$nameinplaceholder} ({$lang['name']})</label><div><{$options['tag']} value='$value' placeholder='Enter In {$lang['name']}' name='{$options['name']}{$lang['code']}' class='form-control {$options['class']}' id='{$options['id']}'>$value</{$options['tag']}></div>";  
            }
               
           }
       }
       return $input;
    }


    public static function addlanguageFields($name,$fild="trans",$valdef=""){

        $_POST[$fild][self::$default]=$valdef;
        foreach($_POST[$fild] as $k=>$trs){
            $val=$trs==""?$name:$trs;
            self::updateLanguageValue($k,$name,$val);
        }
       
       return ["status"=>true,"msg"=>"Words added successfully"];
    }
    
}
Translator::setLanguages(\DB::table("languages")->get()->toArray());
?>
