<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class JenisModel extends Model implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getKey();
      }
  
      public function getJWTCustomClaims(){
        return [];
      }

    use HasFactory;

    protected $table = 'm_jenis';
    protected $primaryKey = 'jenis_id';
    protected $fillable = ['jenis_kode', 'jenis_nama', 'created_at', 'updated_at'];
}
