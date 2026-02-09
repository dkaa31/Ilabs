<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mapel';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nama', 'kode'];
}