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
    protected $fillable = ['user_id', 'bidang_id', 'mk_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }

    public function matkul()
    {
        return $this->belongsTo(MatkulModel::class, 'mk_id', 'mk_id');
    }
}
