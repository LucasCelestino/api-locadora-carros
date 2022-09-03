<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    private Carro $carro;

    public function __construct(Carro $carroParam)
    {
        $this->carro = $carroParam;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos'))
        {
            $carroRepository->selectAtributosRegistros($request->atributos);
        }
        else
        {
            $carroRepository->selectWith();
        }

        if($request->has('filtro'))
        {
            $carroRepository->filtro($request->filtro);
        }

        return response()->json($carroRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarroRequest $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());

        $carro = $this->carro->create([
            'placa'=>$request->get('placa'),
            'disponivel'=>$request->get('disponivel'),
            'km'=>$request->get('km'),
            'modelo_id'=>$request->get('modelo_id')
        ]);

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $carro = $this->carro->with('modelo')->find($id);

        if($carro == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarroRequest $request, int $id)
    {
        $carro = $this->carro->with('modelo')->find($id);

        if($carro == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        if($request->method() === 'PATCH')
        {
            $regrasDinamicas = array();

            foreach($this->carro->rules() as $key => $value)
            {
                if(array_key_exists($key, $request->all()))
                {
                    $regrasDinamicas[$key] = $value;
                }
            }

            $request->validate($regrasDinamicas, $this->carro->feedback());
        }
        else
        {
            $request->validate($this->carro->rules(), $this->carro->feedback());
        }

        $carro->fill($request->all());

        $carro->save();

        return response()->json(['success'=>'Carro atualizado com sucesso'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $carro = $this->carro->with('modelo')->find($id);

        if($carro == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        $carro->delete();

        return response()->json(['success'=>'Carro deletado com sucesso'], 200);
    }
}
