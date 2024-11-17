<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelatihanModel extends Model
{
    use HasFactory;

    protected $table = 't_pelatihan';
    protected $primaryKey = 'pelatihan_id';
    protected $fillable = ['level_id', 'bidang_id', 'mk_id', 'vendor_id', 'nama_pelatihan', 'tanggal', 'kuota', 'lokasi', 'periode', 'created_at', 'updated_at'];

    public function level()
    {
        return $this->belongsTo(LevelPelatihanModel::class, 'level_id', 'level_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangModel::class, 'bidang_id', 'bidang_id');
    }
    
    public function matkul()
    {
        return $this->belongsTo(MatkulModel::class, 'mk_id', 'mk_id');
    }
    
    public function vendor()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
    }
}