<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todosrec extends Model
{
    // Nama tabel di database
    protected $table = 'todosrec';

    // Nama primary key
    protected $primaryKey = 'TODOID';

    // Timestamps aktif
    public $timestamps = true;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'TODODESC',
        'TODOISDONE',
        'TODOFINISHTIMESTAMP',
        'TODODEADLINEDATE',
    ];
}
