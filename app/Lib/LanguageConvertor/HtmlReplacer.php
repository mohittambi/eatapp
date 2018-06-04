<?php
namespace App\Library\LanguageConvertor;
class HtmlReplacer
{
    public $tagArr = array(
	'doctype',
	'html',
	'head',
	'title',
	'base',
	'link',
	'meta',
	'style',
	'script',
	'noscript',
	'body',
	'article',
	'nav',
	'aside',
	'section',
	'header',
	'footer',
	'h1',
	'h2',
	'h3',
	'h4',
	'h5',
	'h6',
	'main',
	'address',
	'p',
	'hr',
	'pre',
	'blockquote',
	'ol',
	'ul',
	'li',
	'dl',
	'dt',
	'dd',
	'figure',
	'figcaption',
	'div',
	'table',
	'caption',
	'thead',
	'tbody',
	'tfoot',
	'tr',
	'th',
	'td',
	'col',
	'colgroup',
	'form',
	'fieldset',
	'legend',
	'label',
	'input',
	'button',
	'select',
	'datalist',
	'optgroup',
	'option',
	'textarea',
	'keygen',
	'output',
	'progress',
	'meter',
	'details',
	'summary',
	'command',
	'menu',
	'del',
	'ins',
	'img',
	'iframe',
	'embed',
	'object',
	'param',
	'video',
	'audio',
	'source',
	'canvas',
	'track',
	'map',
	'area',
	'a',
	'em',
	'strong',
	'i',
	'b',
	'u',
	's',
	'small',
	'abbr',
	'q',
	'cite',
	'dfn',
	'sub',
	'sup',
	'time',
	'code',
	'kbd',
	'samp',
	'var',
	'mark',
	'bdi',
	'bdo',
	'ruby',
	'rt',
	'rp',
	'span',
	'br',
	'wbr',
        "?php"
);
    public $file,$file_to;
    public $ignoreTags=[
//                        ['start'=>"<script(.*?)>","end"=>"</script>"],
                        ['start'=>"<style(.*?)>","end"=>"</style>"],
        ['start'=>"<!--","end"=>"-->"],
//        ['start'=>"&","end"=>";"],
        
        
                       ];
    public $replaceBy =['start'=>"<?php echo _t('","end"=>"'); ?>"];
    
    
    function __construct($file,$file_to="") {
        $this->file=$file;
        if($file_to==""){
            $this->file_to=$this->file;
        }
        else
            $this->file_to=$file_to;
        
    }
   
    function addIgnoreTags($tag){
        array_push($this->ignoreTags, $tag);
        return $this;
//       $this->ignoreTags[]=$tag;
    }
    function replaceBy($tag){
        $this->replaceBy=$tag;
        return $this;
    }
    public  function putInVersionFile($words){
           $data="<?php return ". var_export($words,true)." ?>";
           
           return file_put_contents(dirname(__FILE__)."/version.php",$data);
    }
    function stripArgumentFromTags( $htmlString ) {
        $regEx = '/([^<]*<\s*[a-z](?:[0-9]|[a-z]{0,9}))(?:(?:\s*[a-z\-]{2,14}\s*=\s*(?:"[^"]*"|\'[^\']*\'))*)(\s*\/?>[^<]*)/i'; // match any start tag

        $chunks = preg_split($regEx, $htmlString, -1,  PREG_SPLIT_DELIM_CAPTURE);
        $chunkCount = count($chunks);

        $strippedString = '';
        for ($n = 1; $n < $chunkCount; $n++) {
            $strippedString .= $chunks[$n];
        }

        return $strippedString;
    }
    function convert(){
        $version = include_once(dirname(__FILE__)."/version.php");
        $filepth=  str_replace("\\","/" , $this->file);
        if(in_array($filepth,$version)){
            if($version[$filepth]==filemtime($filepth)){
                return 1;
            }
            else{
                $version[$filepth]=filemtime($filepth);
            }
        }
        else{
            $version[$filepth]=filemtime($filepth);
        }
        
        
        
        $js=$css="";
        $store="";$tag=$notConsider=$tagProbability=false;
        $fileoriginal = file_get_contents($this->file);
        $file=$fileoriginal;
//        dd($this->ignoreTags);

        foreach ($this->ignoreTags as $ignoreTag){
        
            $file = preg_replace('#'.$ignoreTag['start'].'(.*?)'.$ignoreTag['end'].'#is', '', $file);
             
        }
        
        $file= $this->stripArgumentFromTags($file);

        $text="";$all_text=[];
        $storeTag="";
        for ($i=0;$i<=strlen($file);$i++)
          {
           
             if(!isset($file[$i])){
                break;
            }
             $c=$file[$i];
            if($c=="<" ){
                if(trim($text)!=""){
                    
                    $all_text[]=trim(preg_replace('/\s\s+/', ' ', $text));;
                    $text="";
                }
                $tagProbability     = true;
                $storeTag="";
            }
            else if($c==">" ){
                 $store        .= $storeTag.$c;
                 $tag           = false;
                 $tagProbability= false;
            }
            else if($c==" " ){
                if($tagProbability){
                    if(in_array($storeTag,$this->tagArr)){
                        $tag           = true;
                        $tagProbability= false;
                        if(strtolower($storeTag)=="script" || strtolower($storeTag)=="style")
                        {
                            $notConsider=true;
                        }
                        $store.="<$storeTag ";
                    }
                }
                else{
                    $text.=$c;
                    $store.=$c;
                }
                
            }
            else if($tag){
                 $store.=$c;
            }
            else if($tagProbability){
                $storeTag.=$c;
            }
            else
                {
                $text.=$c;
                $store.=$c;
            }
          }

      $fileoriginalFnl="";
    
        foreach($all_text as $k=>$txt){
            $find=strpos( $fileoriginal,$txt);
            if($find!==FALSE ){
                $strlen           = strlen($txt);
                $repalaceStr      = $this->replaceBy['start'].$txt.$this->replaceBy['end'];
           
                $strlenAftr       = strlen($repalaceStr);
                $replaced         = substr_replace($fileoriginal,$repalaceStr,$find,$strlen);
                $fileoriginalFnl.= substr($replaced,0,$find+$strlenAftr);
                $fileoriginal     = substr($fileoriginal,$find+$strlen,strlen($fileoriginal));
            }
        }
        $fileoriginalFnl.=$fileoriginal;
        $this->putInVersionFile($version);
        return file_put_contents($this->file_to,$fileoriginalFnl);
        
    }
}
