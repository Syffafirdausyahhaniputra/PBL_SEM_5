<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BidangModel extends Model
{

    protected $table = 'm_bidang';
    protected $primaryKey = 'bidang_id';
    protected $fillable = ['bidang_kode', 'bidang_nama', 'created_at', 'updated_at'];

}