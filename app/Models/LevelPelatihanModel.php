<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 'm_level_pelatihan';
    protected $primaryKey = 'level_id';
    protected $fillable = ['level_kode', 'level_nama', 'created_at', 'updated_at'];
}
