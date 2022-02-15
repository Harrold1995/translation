<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Translator extends Model{

   protected $table = 'translators';
   protected $fillable = ['name','surname','email','password','mobile'];

 	  
  	/* 
	* Relationship
	*/

	public function languages()
    {
        return $this->belongsToMany('App\Models\Language','translator_languages');
	}

	public function documents()
    {
        return $this->belongsToMany('App\Models\Document', 'document_translators');
	}

	public function languagePair()
    {
        return $this->hasMany('App\Models\LanguagePair');
	}

	public function TranslatorLanguage()
    {
        return $this->hasMany('App\Models\TranslatorLanguage');
	}

	public function AccreditationProvider()
    {
        return $this->belongsToMany('App\Models\AccreditationProvider','translator_accreditation_providers')->withPivot('accreditation_number');
	}

	public function Association()
    {
        return $this->belongsToMany('App\Models\Association','translator_associations')->withPivot('member_number');
	}

	public function terms()
    {
        return $this->belongsToMany('App\Models\Term','term_translator');
	}

	public function address()
    {
        return $this->belongsToMany('App\Models\Address','translator_addresses','translator_id','address_id');
    }

	/* 
	* Random Password
	*/
  function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

}