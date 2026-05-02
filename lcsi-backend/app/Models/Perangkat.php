<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    protected $table = 'perangkat';
    protected $primaryKey = 'id_perangkat';

    protected $fillable = [
        'nama_perangkat',
        'merk',
        'serial_number',
        'terpasang di',
        'status',
    ];
}
