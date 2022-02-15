<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;
use App\Plugins\Plugin;


class Block extends Model
{
    protected $table = 'cms_blocks';
    protected $fillable = ['cms_system_id','name','type','html'];
    public $timestamps = false;

    /* 
	* Relationship
    */
    public function system()
    {
        return $this->belongsTo('App\Models\CMS\System','cms_system_id');
    }

    public function templateTagsContentLink()
    {
        return $this->hasMany('App\Models\CMS\TemplateTagsContentLink','cms_block_id');
    }

    public function pageContentLink()
    {
        return $this->hasMany('App\Models\CMS\PageContentLink','cms_block_id');
    }

    public function html( )
	{
      
        $pattern = '#\{\{(.*?)\}\}#';
        $text = [$this->html];
    
        foreach($text as $val){
            if (preg_match_all($pattern, $val, $matches)) {
                $encoded = array();
                foreach ($matches[1] as $match) {
                    $encoded[$match] = Plugin::$match();
                }
                
                foreach($matches[1] as $match)
                {
                    if(isset($encoded[$match]))
                    {
                        $val = str_replace('{{'.$match.'}}', $encoded[$match], $val);
                    }
                }
                $textArr[] = $val;
            } else {
                $textArr[] = $val;
            }
           
        }
        
		return $textArr[0];
	}

}
