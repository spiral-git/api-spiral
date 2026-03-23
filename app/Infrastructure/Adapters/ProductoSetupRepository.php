<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ISetupProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoSetupRepository implements ISetupProductoRepository
{
    public string $table;

    private array $translations = [

    "es" => [
        "product_setup_created" => "Setup de producto creado correctamente",
        "error" => "Ocurrió un error",
        "setup_product_found" => "Setup del producto encontrado",
        "setup_product_not_found" => "Setup del producto no encontrado",
    ],

    "en" => [
        "product_setup_created" => "Product setup created successfully",
        "error" => "An error occurred",
        "setup_product_found" => "Product setup found",
        "setup_product_not_found" => "Product setup not found",
    ]

];

    public function __construct()
    {
        $this->table = "PRODUCTO_SETUP";
    }

     public function GetById(int $id, string $lang): RespuestaEntity
    {
        try {

            $row = DB::table($this->table)
                ->where('id', $id)
                ->first();

            if (! $row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['setup_product_not_found'] ?? '',
                    false,
                    null
                );
            }

            $producto = new ProductoSetupEntity;
            $producto->Id = $row->id;
            $producto->Amount = $row->amount;
            $producto->IdTipoSetup = $row->id_tipo_setup;

            return new RespuestaEntity(
                $this->translations[$lang]['setup_product_found'] ?? '',
                true,
                $producto
            );

        } catch (Exception $e) {

            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function Create(ProductoSetupEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $id = DB::table($this->table)->insertGetId([
                'id_tipo_setup' => $entity->IdTipoSetup,
                'amount' => $entity->Amount
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['product_setup_created'] ?? "",
                true,
                $entity
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}