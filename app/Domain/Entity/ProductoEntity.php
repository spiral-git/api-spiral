<?php
namespace App\Domain\Entity;

use DateTime;

class ProductoEntity
{
    public int $Id;
    public int $IdTipoProducto;
    public int $IdTipoPago;
    public int $IdLenguaje;
    public string $Nombre;
    public string $Descripcion;
    public int $ValoracionGeneral;
    public DateTime $CreateAt;
    public string $Status;


    public function __construct() { }
}
