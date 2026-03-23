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

            // 🔒 Sanitizar dir
            $dir = preg_replace('/[^A-Za-z0-9_\-]/', '', $dir);
            $dir = $dir ?: 'default';

            $resp = $this->validarTokenHeaderAll($lang);

            if (! $resp->IsSuccess) {
                return response()->json($resp, Response::HTTP_UNAUTHORIZED);
            }

            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,pdf|max:51200',
            ]);

            $file = $request->file('file');

            // 🔥 nombre seguro
            $filename = bin2hex(random_bytes(16)).'.'.$file->getClientOriginalExtension();

            $destinationPath = public_path("uploads/$dir");

            if (! is_dir($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            $file->move($destinationPath, $filename);

            $url = asset("uploads/$dir/$filename");
 
            $respuesta = new RespuestaEntity(
                $this->translations[$lang]['success'] ?? '',
                true,
                [
                    'url' => $url,
                    'name' => $filename,
                    'type' => $file->getClientMimeType(),
                ]
            );

            return response()->json($respuesta, Response::HTTP_CREATED);

        } catch (Exception $e) {

            $respuesta = new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );

            return response()->json($respuesta, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
