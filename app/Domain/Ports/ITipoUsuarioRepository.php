<?php

namespace App\Domain\Ports;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoUsuarioEntity;

interface ITipoUsuarioRepository
{

    public function Create(TipoUsuarioEntity $tipoUsuario, string $lang): RespuestaEntity;
    public function GetAll(string $lang): RespuestaEntity;
    public function GetByName(string $name, string $lang): RespuestaEntity;

}
