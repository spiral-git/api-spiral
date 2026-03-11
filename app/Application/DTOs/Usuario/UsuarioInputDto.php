<?php

namespace App\Application\DTOs\Usuario;

class UsuarioInputDto
{
    public string $Nombres;
    public string $Apellidos;
    public string $Password;
    public string $Correo;
    public string $Imagen;
    public string $Telefono;

    public function __construct() {}
}
