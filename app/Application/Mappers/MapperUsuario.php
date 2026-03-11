<?php

namespace App\Application\Mappers;

use App\Application\DTOs\Usuario\UsuarioInputDto;
use App\Domain\Entity\UsuarioEntity;

class MapperUsuario
{
    
    public static function inputDtoToEntity(UsuarioInputDto $dto): UsuarioEntity
    {
        $usuario = new UsuarioEntity();
        $usuario->Nombres = $dto->Nombres;
        $usuario->Apellidos = $dto->Apellidos;
        $usuario->Password = $dto->Password;
        $usuario->Correo = $dto->Correo;
        $usuario->Imagen = $dto->Imagen;
        $usuario->Telefono = $dto->Telefono;

        return $usuario;
    }

}
