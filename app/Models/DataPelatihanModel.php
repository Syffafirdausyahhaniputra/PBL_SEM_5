<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPelatihanModel extends Model
{
    use HasFactory;

    protected $table = 't_data_pelatihan';
    protected $primaryKey = 'data_pelatihan_id';
    protected $fillable = ['pelatihan_id', 'dosen_id', 'status', 'created_at', 'updated_at'];

    public function pelatihan()
    {
        return $this->belongsTo(PelatihanModel::class, 'pelatihan_id', 'pelatihan_id');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }
}