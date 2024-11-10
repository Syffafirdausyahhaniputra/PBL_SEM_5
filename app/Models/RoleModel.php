<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'm_role';
    protected $primaryKey = 'role_id';

    /**
     * The attributes that are mass assignable
     * 
     * @var array
     */
    
    protected $fillable = ['role_id', 'role_kode', 'role_nama'];
    
    public function user():BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }
}
