<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ProductoVarianteEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoVarianteRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoVarianteRepository implements IProductoVarianteRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "product_variant_created" => "Producto variante creado correctamente",
            "product_variant_updated" => "Producto variante actualizado correctamente",
            "product_variant_not_found" => "Producto variante no encontrado",
            "product_variant_found" => "Producto variante encontrado",
            "record_not_found_update" => "No se encontró el registro para actualizar",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "product_variant_created" => "Product variant created successfully",
            "product_variant_updated" => "Product variant updated successfully",
            "product_variant_not_found" => "Product variant not found",
            "product_variant_found" => "Product variant found",
            "record_not_found_update" => "Record not found for update",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "PRODUCTO_VARIANTE";
    }

    public function Create(ProductoVarianteEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $id = DB::table($this->table)->insertGetId([
                'sku_producto' => $entity->SkuProducto,
                'precio' => $entity->Precio,
                'descuento' => $entity->Descuento,
                'nombre' => $entity->Nombre
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['product_variant_created'] ?? "",
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

    public function Update(ProductoVarianteEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->update([
                    'sku_producto' => $entity->SkuProducto,
                    'precio' => $entity->Precio,
                    'descuento' => $entity->Descuento,
                    'nombre' => $entity->Nombre
                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['record_not_found_update'] ?? "",
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['product_variant_updated'] ?? "",
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
                    $this->translations[$lang]['product_variant_not_found'] ?? "",
                    false,
                    null
                );
            }

            $entity = new ProductoVarianteEntity();
            $entity->Id = $row->id;
            $entity->SkuProducto = $row->sku_producto;
            $entity->Precio = $row->precio;
            $entity->Descuento = $row->descuento;
            $entity->Nombre = $row->nombre;

            return new RespuestaEntity(
                $this->translations[$lang]['product_variant_found'] ?? "",
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