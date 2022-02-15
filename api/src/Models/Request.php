<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Request extends Model{

   protected $table = 'requests';
   protected $fillable = ['description','title','status'];

   public function association_request()
    {
        return $this->hasOne('App\Models\AssociationRequest');
    }

}