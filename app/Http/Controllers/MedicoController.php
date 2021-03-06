<?php

namespace App\Http\Controllers;

use App\Services\EspecialidadService;
use App\Services\MedicoService;
use App\Http\Requests\MedicoRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * Class MedicoController
 * @package App\Http\Controllers
 */
class MedicoController extends Controller
{
    use RegistersUsers;
    use SendsPasswordResetEmails;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/medicos';

    /**
     * @var MedicoService
     */
    protected $medicoService;
    /**
     * @var EspecialidadService
     */
    protected $especialidadService;

    /**
     * MedicoController constructor.
     * @param MedicoService $medicoService
     * @param EspecialidadService $especialidadService
     */
    public function __construct(
        MedicoService $medicoService,
        EspecialidadService $especialidadService
    )
    {
        $this->medicoService = $medicoService;
        $this->especialidadService = $especialidadService;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param MedicoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(MedicoRequest $request)
    {
        $input = $request->validated();
        $medico = $this->medicoService->create($input);
        event(new Registered($medico->user));
        $this->sendResetLinkEmail($request);
        return redirect($this->redirectPath());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicos = $this->medicoService->findAll();
        return view('medico.index', ['medicos' => $medicos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $especialidades = $this->especialidadService->findAll();
        return view('medico.create', ['especialidades' => $especialidades]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medico = $this->medicoService->find($id);
        $especialidades = $this->especialidadService->findAll();
        return view('medico.edit', [
            'medico' => $medico,
            'especialidades' => $especialidades
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MedicoRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MedicoRequest $request, $id)
    {
        $medico = $this->medicoService->find($id);
        $validatedData = $request->validated();
        $this->medicoService->update($validatedData, $medico);
        return redirect('/admin/medicos')->with('status', 'Médico editado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $medico = $this->medicoService->find($id);
        $this->medicoService->destroy($medico);
        return response()->json(200);
    }
}
