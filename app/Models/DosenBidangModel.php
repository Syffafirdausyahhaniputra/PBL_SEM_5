<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class DosenBidangModel extends Model implements JWTSubject
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

    protected $table = 'm_dosen_bidang';
    protected $primaryKey = 'dosen_bidang_id';
    protected $fillable = ['dosen_id', 'bidang_id', 'created_at', 'updated_at'];

    public function dosen()
    {
        return $this->belongsTo(UserModel::class, 'dosen_id', 'dosen_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }
}
