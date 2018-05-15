<?php 
/**
   * Laravel tranlator trait
   * @package    Laravel Translator
   * @author     Aman Jain <aman.jain@ninehertzindia.com>
   */
namespace App\Lib\LanguageConvertor;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
trait Translatable {
    //public sta$tranlableColumns;
    static $lang="en";
    static $langauseTableName="languages";
    
    function  __construct(){
        
       
        $this->translate(self::$translatableColumns);
    }

    /**
     * Change language at runtime.
     *
     * @param  String  $lang
     
     * @return void
     */
    
    public static function setLocale($lang) { 
         self::$lang=$lang;  
    }
    
     
   
     
    /**
     * add Events of retrive ,update , delete and save . whenever a model is update retrive ,update , delete and save it changes the data in translation table.
     *
     * @param  Array  $arr
     
     * @return void
     */
     

     function translate($arr) { 
       
        $object   = $this;
        $table    = $object->getTable(); 
        $primary  = $object->getKeyName(); 
       
        self::$translatableColumns=$arr;
        $Translation=\DB::table("translation");
        $translations=\DB::table(self::$langauseTableName)->get();

       
        static::addGlobalScope(new LanguageScope($table, $primary,self::$lang,self::$translatableColumns ));
      
        
        self::saved (function($model) use($primary,$table,$translations){
          
            foreach($translations as $translation){
                foreach(self::$translatableColumns as $translatableColumn){
                    if(Input::get("translation_".$translatableColumn."_".$translation->code)){
                       
                      $Translation=\DB::table("translation");
                      
                      $TranslationQry= $Translation 
                        ->where("fk",$model->{$primary})
                        ->where("table_name",$table)
                        ->where("column_name",$translatableColumn)
                        ->where("locale",$translation->code);
                        if($TranslationQry->count()>0){
                            $TranslationQry->update(['value'=>Input::get("translation_".$translatableColumn."_".$translation->code)]);
                        }else{
                            $Translation-> insert(
                                                    ["fk"=>$model->{$primary},
                                                    "table_name"=> $table,
                                                    "column_name"=>$translatableColumn,
                                                    "locale"=>$translation->code,
                                                    "value"=>Input::get("translation_".$translatableColumn."_".$translation->code),
                                                    ]
                                                );
                        }

                          
                    }
                }
            }
          
        });
       

      }   
}

//Larvael Scope for global
class LanguageScope implements Scope
{
   
    function __construct($table, $primary,$lang,$tranlableColumns ){
       $this->table= $table;
       $this->primary =  $primary ;
       $this->lang =  $lang ;
       $this->tranlableColumns =  $tranlableColumns ;
       
      
    }
     /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $object, Model $model)
    {
        $columns  = $this->tranlableColumns ;
        $table    = $this->table; 
        $object->addSelect(\DB::raw("$table.*"));
        foreach($columns as $column){
            $object->leftJoin(\DB::raw("translation as translation_$column"), function($join) use($object,$column )
            {
                $table    = $this->table; 
                $primary  = $this->primary ; 
            
            
                $join->on("translation_$column.fk", '=',  $table.'.'.$primary);
                $join->on("translation_$column.table_name", '=',  \DB::raw("'$table'"));
                $join->on("translation_$column.column_name", '=',  \DB::raw("'$column'"));
                $join->on("translation_$column.locale", '=',\DB::raw("'$this->lang'") );
            })->addSelect(\DB::raw("ifnull(translation_$column.value,$table.$column) as $column"));
            
        }
         
        
       
    }
}
