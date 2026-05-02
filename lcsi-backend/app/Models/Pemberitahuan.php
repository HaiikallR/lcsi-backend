<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemberitahuan extends Model
{

    protected $fillable = [
        'judul',
        'isi_pesan',
        'kategori',
    ];
}
