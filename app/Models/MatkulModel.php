<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatkulModel extends Model
{
    use HasFactory;

    protected $table = 'm_matkul';
    protected $primaryKey = 'mk_id';
    protected $fillable = ['mk_kode', 'mk_nama', 'created_at', 'updated_at'];
}
