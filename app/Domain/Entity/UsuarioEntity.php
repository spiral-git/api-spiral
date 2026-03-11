<?php

namespace App\Domain\Entity;

class UsuarioEntity
{
    public int $Id;
    public int $IdTipoUsuario;
    public string $Nombres;
    public string $Apellidos;
    public string $Password;
    public string $Correo;
    public string $Imagen;
    public string $Telefono;
    public bool $Status;

    public function __construct(){}
    
}
