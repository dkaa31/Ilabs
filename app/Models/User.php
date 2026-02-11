<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'userable_id',
        'userable_type',
    ];

    protected $hidden = ['password'];

    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    public function userable()
    {
        return $this->morphTo();
    }

    // Helper
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isGuru()
    {
        return $this->role === 'guru';
    }
    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
