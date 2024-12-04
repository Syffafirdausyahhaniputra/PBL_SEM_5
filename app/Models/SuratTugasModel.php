<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class SuratTugasModel extends Model implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getKey();
      }
  
      public function getJWTCustomClaims(){
        return [];
      }

    use HasFactory;

    protected $table = 'm_surat_tugas';
    protected $primaryKey = 'surat_tugas_id';
    protected $fillable = ['nomor_surat', 'nama_surat', 'status', 'created_at', 'updated_at'];
}
