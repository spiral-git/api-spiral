<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ProductoBasicoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoBasicoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoBasicoRepository implements IProductoBasicoRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "basic_created" => "Producto básico creado correctamente",
            "basic_updated" => "Producto básico actualizado correctamente",
            "basic_found" => "Producto básico encontrado",
            "basic_not_found" => "Producto básico no encontrado",
            "basic_not_updated" => "No se encontró el registro para actualizar",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "basic_created" => "Basic product created successfully",
            "basic_updated" => "Basic product updated successfully",
            "basic_found" => "Basic product found",
            "basic_not_found" => "Basic product not found",
            "basic_not_updated" => "Record not found for update",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "PRODUCTO_BASICO";
    }

    public function Create(ProductoBasicoEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $id = DB::table($this->table)->insertGetId([
                'sku_producto' => $entity->SkuProducto,
                'precio' => $entity->Precio,
                'descuento' => $entity->Descuento,
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['basic_created'] ?? "",
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

    public function Update(ProductoBasicoEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->update([
                    'sku_producto' => $entity->SkuProducto,
                    'precio' => $entity->Precio,
                    'descuento' => $entity->Descuento,
                    'id_tipo_descuento' => $entity->IdTipoDescuento
                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['basic_not_updated'] ?? "",
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['basic_updated'] ?? "",
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
                ->where('sku_producto', $sku)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['basic_not_found'] ?? "",
                    false,
                    null
                );
            }

            $entity = new ProductoBasicoEntity();
            $entity->Id = $row->id;
            $entity->SkuProducto = $row->sku_producto;
            $entity->Precio = $row->precio;
            $entity->Descuento = $row->descuento;

            return new RespuestaEntity(
                $this->translations[$lang]['basic_found'] ?? "",
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