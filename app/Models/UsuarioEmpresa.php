<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UsuarioEmpresa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tb_usuario_empresa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ruc_empresa',
        'email_usuario',
        'usuario',
        'contrasena',
        'menu_usuario'
    ];

    protected $hidden = [
        'contrasena',
    ];

    // Método para encriptar la contraseña si lo requieres
    public function setPasswordAttribute($value)
    {
        $this->attributes['contrasena'] = bcrypt($value);
    }

    // Especifica el campo de la contraseña para la autenticación
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}
