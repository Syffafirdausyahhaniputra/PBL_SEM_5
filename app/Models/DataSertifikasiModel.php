<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class DataSertifikasiModel extends Model implements JWTSubject
{
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  use HasFactory;

  protected $table = 't_data_sertifikasi';
  protected $primaryKey = 'data_sertif_id';
  protected $fillable = ['sertif_id', 'dosen_id', 'surat_tugas_id', 'sertifikat', 'created_at', 'updated_at'];

  public function sertifikasi()
  {
      return $this->belongsTo(SertifikasiModel::class, 'sertif_id', 'sertif_id');
  }

  public function dosen()
  {
      return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
  }

  // Di dalam DataSertifikasiModel
  public function sertif()
  {
    return $this->belongsTo(SertifikasiModel::class, 'sertif_id');
  }

  public function surat_tugas()
  {
    return $this->belongsTo(SuratTugasModel::class, 'surat_tugas_id');
  }
}
