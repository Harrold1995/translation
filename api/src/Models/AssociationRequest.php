<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class AssociationRequest extends Model{

   protected $table = 'association_requests';
   protected $fillable = ['association_id','request_id','translator_id','file_id','association_name'];

   public function request()
    {
        return $this->belongsTo('App\Models\Request');
    }

}