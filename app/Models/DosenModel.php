<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class DosenModel extends Model implements JWTSubject
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

    protected $table = 'm_dosen';
    protected $primaryKey = 'dosen_id';
    protected $fillable = ['user_id', 'jabatan_id', 'golongan_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
    public function dosenBidang()
    {
        return $this->hasOne(DosenBidangModel::class, 'dosen_id', 'dosen_id');
    }
    
    public function dosenMatkul()
    {
        return $this->belongsTo(DosenMatkulModel::class, 'dosen_id', 'dosen_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanModel::class, 'Jabatan_id', 'Jabatan_id');
    }

    public function golongan()
    {
        return $this->belongsTo(GolonganModel::class, 'golongan_id', 'golongan_id');
    }
}
