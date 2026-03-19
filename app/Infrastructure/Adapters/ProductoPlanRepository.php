<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ProductoPlanEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoPlanRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoPlanRepository implements IProductoPlanRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "plan_created" => "Producto plan creado correctamente",
            "plan_updated" => "Producto plan actualizado correctamente",
            "plan_found" => "Producto plan encontrado",
            "plan_not_found" => "Producto plan no encontrado",
            "plan_not_updated" => "No se encontró el registro para actualizar",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "plan_created" => "Product plan created successfully",
            "plan_updated" => "Product plan updated successfully",
            "plan_found" => "Product plan found",
            "plan_not_found" => "Product plan not found",
            "plan_not_updated" => "Record not found for update",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "PRODUCTO_PLAN";
    }

    public function Create(ProductoPlanEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $id = DB::table($this->table)->insertGetId([
                'sku_producto' => $entity->SkuProducto,
                'precio' => $entity->Precio,
                'descuento' => $entity->Descuento,
                'nombre' => $entity->Nombre,
                'descripcion' => $entity->Descripcion,
                'etiqueta' => $entity->Etiqueta,
                'id_tipo_descuento' => $entity->IdTipoDescuento
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['plan_created'] ?? "",
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

    public function Update(ProductoPlanEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->update([
                    'sku_producto' => $entity->SkuProducto,
                    'precio' => $entity->Precio,
                    'descuento' => $entity->Descuento,
                    'nombre' => $entity->Nombre,
                    'descripcion' => $entity->Descripcion,
                    'etiqueta' => $entity->Etiqueta,
                    'id_tipo_descuento' => $entity->IdTipoDescuento

                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['plan_not_updated'] ?? "",
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['plan_updated'] ?? "",
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
                    $this->translations[$lang]['plan_not_found'] ?? "",
                    false,
                    null
                );
            }

            $entity = new ProductoPlanEntity();
            $entity->Id = $row->id;
            $entity->SkuProducto = $row->sku_producto;
            $entity->Precio = $row->precio;
            $entity->Descuento = $row->descuento;
            $entity->Nombre = $row->nombre;
            $entity->Descripcion = $row->descripcion;
            $entity->Etiqueta = $row->etiqueta;
            $entity->IdTipoDescuento = $row->id_tipo_descuento;


            return new RespuestaEntity(
                $this->translations[$lang]['plan_found'] ?? "",
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