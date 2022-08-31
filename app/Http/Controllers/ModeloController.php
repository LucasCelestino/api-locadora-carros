<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Http\Requests\StoreModeloRequest;
use App\Http\Requests\UpdateModeloRequest;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    private Modelo $modelo;

    public function __construct(Modelo $modeloParam)
    {
        $this->modelo = $modeloParam;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modelos = $this->modelo->with('marca')->get();

        return response()->json($modelos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreModeloRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreModeloRequest $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $imagem = $request->file('imagem');

        $urn = $imagem->store('imagens/modelos', 'public');

        $modelo = Modelo::create([
            'marca_id'=>$request->get('marca_id'),
            'nome'=>$request->get('nome'),
            'imagem'=>$urn,
            'numero_portas'=>$request->get('numero_portas'),
            'lugares'=>$request->get('lugares'),
            'air_bag'=>$request->get('air_bag'),
            'abs'=>$request->get('abs')
        ]);

        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if($modelo == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateModeloRequest  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateModeloRequest $request, $id)
    {
        $modelo = Modelo::find($id);

        if($modelo == null)
        {
            return response()->json(['error'=>'Nao foi possivel encontrar o recurso solicitado'], 404);
        }

        if($request->method() === 'PATCH')
        {
            $regrasDinamicas = [];

            foreach($this->modelo->rules() as $key => $value)
            {
                if(array_key_exists($key, $request->all()))
                {
                    $regrasDinamicas[$key] = $value;
                }
            }

            $request->validate($regrasDinamicas, $this->modelo->feedback());
        }
        else
        {
            $request->validate($this->modelo->rules(), $this->modelo->feedback());
        }

        if($request->file('imagem'))
        {
            Storage::disk('public')->delete($modelo->imagem);

            $imagem = $request->file('imagem');

            $urn = $imagem->store('imagens/modelos', 'public');

            $modelo->fill($request->all());

            $modelo->imagem = $urn;
        }
        else
        {
            $modelo->fill($request->all());
        }

        $modelo->save();

        return response()->json(['success'=>'Modelo atualizado com sucesso'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $modelo = Modelo::find($id);

        if($modelo == null)
        {
            return response()->json(['error'=>'Nao foi possivel encontrar o recurso solicitado'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();

        return response()->json(['success'=>'Modelo deletado com sucesso'], 200);
    }
}
