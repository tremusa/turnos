<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PacienteResource;
use App\Services\PacienteService;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    protected $pacienteService;

    public function __construct(PacienteService $pacienteService)
    {
        $this->pacienteService = $pacienteService;
    }

    /**
     * Ingreso del paciente al sistema publico de turnos
     *
     * @param Request $request
     * @return PacienteResource
     */
    public function ingreso(Request $request)
    {
        $paciente = $this->pacienteService->findByDocumento(
            $request->get('documento')
        );
        return new PacienteResource($paciente);
    }
}
