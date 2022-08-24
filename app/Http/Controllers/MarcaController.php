<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use Ramsey\Uuid\Type\Integer;

class MarcaController extends Controller
{

    private Marca $marca;

    public function __construct(Marca $marcaParam)
    {
        $this->marca = $marcaParam;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = Marca::all();

        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMarcaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarcaRequest $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $marca = $this->marca->create($request->all());

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $marca = $this->marca->find($id);

        if($marca == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMarcaRequest  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarcaRequest $request,$id)
    {
        $marca = $this->marca->find($id);

        dd($request->nome);

        if($marca == null)
        {
            return response()->json(['error'=>'Nao foi possivel encontrar o recurso solicitado'], 404);
        }

        if($request->method() === 'PATCH')
        {
            $regrasDinamicas = [];

            foreach($request->all() as $key => $value)
            {
                echo $key.' : '.$value;
                echo '<br>';
            }
        }
        else
        {
            $request->validate($this->marca->rules(), $this->marca->feedback());
        }

        $marca->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marca $marca)
    {
        //
    }
}
