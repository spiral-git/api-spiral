<?php

namespace App\Http\Controllers;

use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use App\Domain\Entity\RespuestaEntity;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadFileController extends BaseController
{
    private UsuarioService $_usuarioService;

    private TipoUsuarioService $_tipoUsuarioService;

    private array $translations = [
        'es' => [
            'error' => 'Error al subir archivo',
            'success' => 'Archivo subido correctamente',
        ],
        'en' => [
            'error' => 'An error occurred',
            'success' => 'File uploaded successfully',
        ],
    ];

    public function __construct(UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService)
    {
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function uploadFiles(Request $request)
    {
        try {

            $dir = $request->input('dir') ?? 'default';
            $lang = $request->input('lang') ?? 'es';

            $resp = $this->validarTokenHeaderAll($lang);

            if (! $resp->IsSuccess) {
                return response()->json(
                    $resp,
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,pdf|max:51200',
            ]);
            
            $file = $request->file('file');

            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs("uploads/$dir", $filename, 'public');

            $url = asset('storage/'.$path);

            $respuesta = new RespuestaEntity(
                $this->translations[$lang]['success'] ?? '',
                true,
                $url
            );

            return response()->json(
                $respuesta,
                Response::HTTP_CREATED
            );

        } catch (Exception $e) {

            $respuesta = new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );

            return response()->json(
                $respuesta,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
