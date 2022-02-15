<?php
namespace App\Models\CMS;
use Illuminate\Database\Eloquent\Model;

use App\Services\html\tag;


class TemplateTagProperty extends Model
{
    protected $table = 'cms_template_tag_properties';
    protected $fillable = ['cms_template_tag_id','property','value'];
    public $timestamps = false;

    /* 
	* Relationship
    */
    public function templateTag()
    {
        return $this->belongsTo('App\Models\CMS\TemplateTag','cms_template_tag_id');
    }

    public function add( tag $tag )
	{
		$tag->add( $this->property, $this->value );
	}

}
