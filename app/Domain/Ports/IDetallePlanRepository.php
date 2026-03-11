<?php

namespace App\Domain\Ports;

use App\Domain\Entity\DetallePlanEntity;
use App\Domain\Entity\RespuestaEntity;

interface IDetallePlanRepository
{
    public function Create(DetallePlanEntity $entity, string $lang): RespuestaEntity;
    public function Update(DetallePlanEntity $entity, string $lang): RespuestaEntity;
    public function Delete(int $id, string $lang): RespuestaEntity;
    public function GetAllByProducto(string $idPlan, string $lang): RespuestaEntity;

}
