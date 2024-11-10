<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisModel extends Model
{
    use HasFactory;

    protected $table = 'm_jenis';
    protected $primaryKey = 'jenis_id';
    protected $fillable = ['jenis_kode', 'jenis_nama', 'created_at', 'updated_at'];
}
