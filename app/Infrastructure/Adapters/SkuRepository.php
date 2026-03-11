<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\SkuProductoEntity;
use App\Domain\Ports\ISkuRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class SkuRepository implements ISkuRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "sku_created" => "Sku creado correctamente",
            "not_found" => "No encontrado",
            "found" => "Encontrado",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "sku_created" => "Sku created successfully",
            "not_found" => "Not found",
            "found" => "Found",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "SKU_PRODUCTO";
    }

    public function Create(SkuProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {

            DB::table($this->table)->insert([
                'sku' => $entity->Sku,
                'id_producto' => $entity->IdProducto,
                'status' => $entity->Status,
                'num_recursos_max' => $entity->MaximoRecursos,
                'id_setup_producto' => $entity->IdSetupProducto
            ]);

            return new RespuestaEntity(
                $this->translations[$lang]['sku_created'] ?? "",
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

    public function GetBySku(string $sku, string $lang): RespuestaEntity
    {
        try {
            $row = DB::table($this->table)
                ->where("sku", $sku)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found'] ?? "",
                    false,
                    null
                );
            }

            $skuEntity = new SkuProductoEntity();
            $skuEntity->Sku = $row->sku;
            $skuEntity->Status = $row->status;
            $skuEntity->IdProducto = $row->id_producto;
            $skuEntity->MaximoRecursos = $row->num_recursos_max;
            $skuEntity->IdSetupProducto = $row->id_setup_producto;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                true,
                $skuEntity
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetByProducto(int $idProducto, string $lang): RespuestaEntity
    {
        try {
            $row = DB::table($this->table)
                ->where("id_producto", $idProducto)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found'] ?? "",
                    false,
                    null
                );
            }

            $skuEntity = new SkuProductoEntity();
            $skuEntity->Sku = $row->sku;
            $skuEntity->Status = $row->status;
            $skuEntity->IdProducto = $row->id_producto;
            $skuEntity->MaximoRecursos = $row->num_recursos_max;
            $skuEntity->IdSetupProducto = $row->id_setup_producto;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                true,
                $skuEntity
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