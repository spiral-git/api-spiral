<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IImagenRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ImagenRepository implements IImagenRepository
{
    public string $table;

    private array $translations = [
        "es" => [
            "image_created" => "Imagen creada correctamente",
            "image_updated" => "Imagen actualizada correctamente",
            "image_not_found_update" => "No se encontró la imagen para actualizar",
            "image_found" => "Imagen encontrada",
            "image_not_found" => "Imagen no encontrada",
            "images_retrieved" => "Imágenes obtenidas correctamente",
            "image_deleted" => "Imagen eliminada correctamente",
            "image_not_found_delete" => "No se encontró la imagen para eliminar",
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "image_created" => "Image created successfully",
            "image_updated" => "Image updated successfully",
            "image_not_found_update" => "No image found to update",
            "image_found" => "Image found",
            "image_not_found" => "Image not found",
            "images_retrieved" => "Images retrieved successfully",
            "image_deleted" => "Image deleted successfully",
            "image_not_found_delete" => "No image found to delete",
            "error" => "An error occurred"
        ]
    ];

    public function __construct()
    {
        $this->table = "IMAGENES_PRODUCTO";
    }

    public function Create(ImagenProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $id = DB::table($this->table)->insertGetId([
                'id_producto' => $entity->IdProducto,
                'ruta' => $entity->Ruta,
                'status' => $entity->Status,
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['image_created'] ?? "",
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

    public function Update(ImagenProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->update([
                    'id_producto' => $entity->IdProducto,
                    'ruta' => $entity->Ruta,
                    'status' => $entity->Status,
                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['image_not_found_update'] ?? "",
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['image_updated'] ?? "",
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

    public function GetByRuta(string $ruta, string $lang): RespuestaEntity
    {
        try {
            $row = DB::table($this->table)
                ->where('ruta', $ruta)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['image_not_found'] ?? "",
                    false,
                    null
                );
            }

            $entity = new ImagenProductoEntity();
            $entity->Id = $row->id;
            $entity->IdProducto = $row->id_producto;
            $entity->Ruta = $row->ruta;
            $entity->Status = $row->status;

            return new RespuestaEntity(
                $this->translations[$lang]['image_found'] ?? "",
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

    public function GetAllByProducto(int $id, string $lang): RespuestaEntity
    {
        try {
            $rows = DB::table($this->table)
                ->where('id_producto', $id)
                ->where('status', true)
                ->get();

            $entities = $rows->map(function ($row) {
                $entity = new ImagenProductoEntity();
                $entity->Id = $row->id;
                $entity->IdProducto = $row->id_producto;
                $entity->Ruta = $row->ruta;
                $entity->Status = $row->status;
                return $entity;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['images_retrieved'] ?? "",
                true,
                $entities
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Delete(int $id, string $lang): RespuestaEntity
    {
        try {
            $updated = DB::table($this->table)
                ->where('id', $id)
                ->update(['status' => false]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['image_not_found_delete'] ?? "",
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['image_deleted'] ?? "",
                true,
                null
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