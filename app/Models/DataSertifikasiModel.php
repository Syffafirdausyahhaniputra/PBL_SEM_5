<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSertifikasiModel extends Model
{
    use HasFactory;

    protected $table = 't_data_sertifikasi';
    protected $primaryKey = 'data_sertif_id';
    protected $fillable = ['sertif_id', 'dosen_id', 'status', 'created_at', 'updated_at'];

    public function sertif()
    {
        return $this->belongsTo(SertifikasiModel::class, 'sertif_id', 'sertif_id');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }
}
