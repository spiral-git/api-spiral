<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\PaisProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IPaisProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class PaisProductoRepository implements IPaisProductoRepository
{
    public string $table;

    private array $translations = [

        'es' => [
            'product_country_created' => 'País del producto creado correctamente',
            'list_retrieved' => 'Registros obtenidos correctamente',
            'record_updated' => 'Registro actualizado correctamente',
            'record_not_found_update' => 'No se encontró el registro para actualizar',
            'record_deleted' => 'Registro eliminado correctamente',
            'record_not_found_delete' => 'No se encontró el registro para eliminar',
            'error' => 'Ocurrió un error',
            "not_found" => "No encontrado",
            "found" => "Encontrado",
        ],

        'en' => [
            'product_country_created' => 'Product country created successfully',
            'list_retrieved' => 'Records retrieved successfully',
            'record_updated' => 'Record updated successfully',
            'record_not_found_update' => 'No record found to update',
            'record_deleted' => 'Record deleted successfully',
            'record_not_found_delete' => 'No record found to delete',
            'error' => 'An error occurred',
            "not_found" => "Not found",
            "found" => "Found",
        ],
    ];

    public function __construct()
    {
        $this->table = 'PAIS_PRODUCTO';
    }

    public function Create(PaisProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $id = DB::table($this->table)->insertGetId([
                'id_pais' => $entity->IdPais,
                'sku' => $entity->SkuProducto,
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['product_country_created'] ?? '',
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

    public function ExistPaisProducto(int $idPais, string $sku, string $lang): RespuestaEntity
    {
        try {
            $row = DB::table($this->table)
                ->where('id_pais', $idPais)
                ->where('sku', $sku)
                ->first();

            if (! $row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found'] ?? '',
                    false,
                    null
                );
            }

            $paisProducto = new PaisProductoEntity;
            $paisProducto->Id = $row->id;
            $paisProducto->IdPais = $row->id_pais;
            $paisProducto->SkuProducto = $row->sku;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? '', 
                true,
                $paisProducto
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }

    public function GetAllBySku(string $sku, string $lang): RespuestaEntity
    {
        try {
            $rows = DB::table($this->table)
                ->where('sku', $sku)
                ->get();

            $entities = [];

            foreach ($rows as $row) {
                $paisProducto = new PaisProductoEntity;
                $paisProducto->Id = $row->id;
                $paisProducto->IdPais = $row->id_pais;
                $paisProducto->SkuProducto = $row->sku;
                $entities[] = $paisProducto;
            }

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

    public function Update(PaisProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->update([
                    'id_pais' => $entity->IdPais,
                    'sku' => $entity->SkuProducto,
                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['record_not_found_update'] ?? '',
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['record_updated'] ?? '',
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

    public function Delete(string $sku, int $idPais, string $lang): RespuestaEntity
    {
        try {
            $deleted = DB::table($this->table)
                ->where('sku', $sku)
                ->where('id_pais', $idPais)
                ->delete();

            if ($deleted === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['record_not_found_delete'] ?? '',
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['record_deleted'] ?? '',
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
