<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_guru';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nama', 'nip', 'foto'];

    public function ruangan()
    {
    return $this->hasOne(Ruangan::class);
    }

    public function kelas()
    {
    return $this->hasOne(Kelas::class, 'id_guru');
    }

    public function user()
    {
    return $this->morphOne(User::class, 'userable');
    }
}

