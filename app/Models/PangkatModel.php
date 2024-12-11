<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class PangkatModel extends Model implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getKey();
      }
  
      public function getJWTCustomClaims(){
        return [];
      }

    use HasFactory;

    protected $table = 'm_pangkat';
    protected $primaryKey = 'pangkat_id';
    protected $fillable = ['pangkat_nama', 'created_at', 'updated_at'];
}
