<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $table = 'pertanyaan';
    protected $primaryKey = 'id_pertanyaan';
    protected $fillable = ['pertanyaan', 'jawaban', 'kategori', 'urutan'];
}
