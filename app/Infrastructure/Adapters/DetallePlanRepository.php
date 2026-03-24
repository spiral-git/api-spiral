<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\DetallePlanEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IDetallePlanRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DetallePlanRepository implements IDetallePlanRepository
{
    public string $table;

    private array $translations = [
        'es' => [
            'detail_created' => 'Detalle plan creado correctamente',
            'detail_updated' => 'Detalle plan actualizado correctamente',
            'detail_not_found_update' => 'No se encontró el registro para actualizar',
            'detail_found' => 'Detalle plan encontrado',
            'detail_not_found' => 'Detalle plan no encontrado',
            'detail_deleted' => 'Detalle eliminado correctamente',
            'detail_not_found_delete' => 'Detalle no encontrado',
            'error' => 'Ocurrió un error',
        ],
        'en' => [
            'detail_created' => 'Plan detail created successfully',
            'detail_updated' => 'Plan detail updated successfully',
            'detail_not_found_update' => 'No record found to update',
            'detail_found' => 'Plan detail found',
            'detail_not_found' => 'Plan detail not found',
            'detail_deleted' => 'Plan detail deleted successfully',
            'detail_not_found_delete' => 'No record found to delete',
            'error' => 'An error occurred',
        ],
    ];

    public function __construct()
    {
        $this->table = 'DETALLES_PLAN';
    }

    public function Create(DetallePlanEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $id = DB::table($this->table)->insertGetId([
                'id_producto_plan' => $entity->IdProductoPlan,
                'detalle' => $entity->Detalle,
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['detail_created'] ?? '',
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

    public function Update(DetallePlanEntity $entity, string $lang): RespuestaEntity
    {
        try {
            $updated = DB::table($this->table)
                ->where('id', $entity->Id)
                ->where('id_producto_plan', $entity->IdProductoPlan)
                ->update([
                    'detalle' => $entity->Detalle,
                ]);

            if ($updated === 0) {
                return new RespuestaEntity(
                    $this->translations[$lang]['detail_not_found_update'] ?? '',
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['detail_updated'] ?? '',
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

    public function GetAllByPlan(string $idPlan, string $lang): RespuestaEntity
    {
        try {

            $rows = DB::table($this->table)
                ->where('id_producto_plan', $idPlan)
                ->get();

            $entities = $rows->map(function ($row) {
                $entity = new DetallePlanEntity;
                $entity->Id = $row->id;
                $entity->IdProductoPlan = $row->id_producto_plan;
                $entity->Detalle = $row->detalle;

                return $entity;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['detail_found'] ?? '',
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
                    $this->translations[$lang]['detail_not_found_delete'] ?? '',
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['detail_deleted'] ?? '',
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
