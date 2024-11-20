<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class VendorModel extends Model implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getKey();
      }
  
      public function getJWTCustomClaims(){
        return [];
      }

    use HasFactory;

    protected $table = 'm_vendor';
    protected $primaryKey = 'vendor_id';
    protected $fillable = ['vendor_nama', 'vendor_alamat', 'vendor_kota', 'vendor_no_telf', 'vendor_alamat_web', 'created_at', 'updated_at'];
}
