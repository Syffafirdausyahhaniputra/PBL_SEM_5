<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class DosenMatkulModel extends Model implements JWTSubject
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

    protected $table = 'm_dosen_matkul';
    protected $primaryKey = 'dosen_matkul_id';
    protected $fillable = ['dosen_id', 'mk_id', 'created_at', 'updated_at'];

    public function dosen()
    {
        return $this->belongsTo(UserModel::class, 'dosen_id', 'dosen_id');
    }

    public function matkul()
    {
        return $this->belongsTo(MatkulModel::class, 'mk_id', 'mk_id');
    }
}
