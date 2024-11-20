<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class SertifikasiModel extends Model implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getKey();
      }
  
      public function getJWTCustomClaims(){
        return [];
      }

    use HasFactory;

    protected $table = 't_sertifikasi';
    protected $primaryKey = 'sertif_id';
    protected $fillable = ['jenis_id', 'bidang_id', 'mk_id', 'vendor_id', 'nama_sertif', 'tanggal', 'masa_berlaku', 'periode', 'created_at', 'updated_at'];

    public function jenis()
    {
        return $this->belongsTo(JenisModel::class, 'jenis_id', 'jenis_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }
    
    public function matkul()
    {
        return $this->belongsTo(MatkulModel::class, 'mk_id', 'mk_id');
    }
    
    public function vendor()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
    }
}
