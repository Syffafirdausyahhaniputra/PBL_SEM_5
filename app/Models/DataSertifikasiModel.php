<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class DataSertifikasiModel extends Model implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getKey();
      }
  
      public function getJWTCustomClaims(){
        return [];
      }
    
    use HasFactory;

    protected $table = 't_data_sertifikasi';
    protected $primaryKey = 'data_sertif_id';
    protected $fillable = ['sertif_id', 'status'];

    public function sertifikasi()
    {
        return $this->belongsTo(SertifikasiModel::class, 'sertif_id', 'data_sertif_id'); // Kolom foreign key dan primary key
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }
}
