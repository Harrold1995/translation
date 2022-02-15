<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Services\curl\post;

class Staff extends Model{

   protected $table = 'staff';
   protected $fillable = ['name','email','password','mobile','otp'];


}