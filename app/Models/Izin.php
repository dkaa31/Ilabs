<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{

    protected $primaryKey = 'id_izin';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_siswa',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'file_surat',
        'status',
        'diproses_oleh'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh', 'id_guru');
    }
}