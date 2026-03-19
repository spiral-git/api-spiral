<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ICategoriaProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoriaProductoRepository implements ICategoriaProductoRepository
{
    public string $table;

    private array $translations = [
        'es' => [
            'created' => 'Categoría del producto creada correctamente',
            'updated' => 'Categoría del producto actualizada correctamente',
            'not_found_update' => 'No se encontró el registro para actualizar',
            'deleted' => 'Categoría del producto eliminada correctamente',
            'not_found_delete' => 'No se encontró el registro para eliminar',
            'list_retrieved' => 'Categorías del producto obtenidas correctamente',
            'error' => 'Ocurrió un error',
            'not_found' => 'No encontrado',
            'found' => 'Encontrado',
        ],
        'en' => [
            'created' => 'Product category created successfully',
            'updated' => 'Product category updated successfully',
            'not_found_update' => 'No record found to update',
            'deleted' => 'Product category deleted successfully',
            'not_found_delete' => 'No record found to delete',
            'list_retrieved' => 'Product categories retrieved successfully',
            'error' => 'An error occurred',
            'not_found' => 'Not found',
            'found' => 'Found',
        ],
    ];

    public function __construct()
    {
        $this->table = 'CATEGORIAS_PRODUCTO';
    }

    public function Create(CategoriaProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $id = DB::table($this->table)->insertGetId([
                'id_producto' => $entity->IdProducto,
                'id_categoria' => $entity->IdCategoria,
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['created'] ?? '',
                true,
                $entity
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function ExistCategoriaProducto(int $idCategoria, string $idProducto, string $lang): RespuestaEntity
    {
        try {
            $row = DB::table($this->table)
                ->where('id_categoria', $idCategoria)
                ->where('id_producto', $idProducto)
                ->first();

            if (! $row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found'] ?? '',
                    false,
                    null
                );
            }

            $entity = new CategoriaProductoEntity;
            $entity->Id = $row->id;
            $entity->IdProducto = $row->id_producto;
            $entity->IdCategoria = $row->id_categoria;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? '',
                true,
                $entity
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function Update(CategoriaProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->update([
                    'id_producto' => $entity->IdProducto,
                    'id_categoria' => $entity->IdCategoria,
                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found_update'] ?? '',
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['updated'] ?? '',
                true,
                $entity
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function GetAllByProducto(int $idProducto, string $lang): RespuestaEntity
    {
        try {
            $rows = DB::table($this->table)
                ->where('id_producto', $idProducto)
                ->get();

            $entities = $rows->map(function ($row) {
                $entity = new CategoriaProductoEntity;
                $entity->Id = $row->id;
                $entity->IdProducto = $row->id_producto;
                $entity->IdCategoria = $row->id_categoria;

                return $entity;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['list_retrieved'] ?? '',
                true,
                $entities
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function Delete(int $id, string $lang): RespuestaEntity
    {
        try {
            $deleted = DB::table($this->table)
                ->where('id', $id)
                ->delete();

            if ($deleted === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found_delete'] ?? '',
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['deleted'] ?? '',
                true,
                null
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }
}
