<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class LevelPelatihanModel extends Model implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getKey();
      }
  
      public function getJWTCustomClaims(){
        return [];
      }

    use HasFactory;

    protected $table = 'm_level_pelatihan';
    protected $primaryKey = 'level_id';
    protected $fillable = ['level_kode', 'level_nama', 'created_at', 'updated_at'];
}
