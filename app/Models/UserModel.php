<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier(){
      return $this->getKey();
    }

    public function getJWTCustomClaims(){
      return [];
    }
    use HasFactory;

    protected $table = 'm_user';        // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id';  // Mendfinisikan primary key dari tabel yang digunakan
    protected $fillable = ['role_id', 'username', 'nama', 'nip', 'avatar', 'password', 'email', 'created_at', 'updated_at'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];


    public function role() : BelongsTo
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'role_id');
    }
    
    public function dosen()
  {
      return $this->hasOne(DosenModel::class, 'user_id', 'user_id');
  }


    /**
     * mendapatkan nama role
     */
    public function getRoleName(): string
    {
      return $this->role->role_nama;
    }

    /**
     * cek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
      return $this->role->role_kode == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
      return $this->role->role_kode;
    }

    protected function image(): Attribute
    {
      return Attribute::make(
        get: fn ($image) => url('/storage/posts/' . $image),
      );
    }
}