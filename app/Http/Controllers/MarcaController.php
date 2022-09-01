<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function index(Request $request)
    {

        $marcas = array();

        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos'))
        {
            $marcaRepository->selectAtributosRegistros($request->atributos);
        }
        else
        {
            $marcaRepository->selectWith();
        }

        if($request->has('filtro'))
        {
            $marcaRepository->filtro($request->filtro);
        }

        return response()->json($marcaRepository->getResultado(), 200);
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

        $imagem = $request->file('imagem');

        $urn = $imagem->store('imagens/marcas', 'public');

        $marca = $this->marca->create([
            'nome'=>$request->get('nome'),
            'imagem'=>$urn
        ]);

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
        $marca = $this->marca->with('modelos')->find($id);

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
        $marca = Marca::find($id);

        if($marca == null)
        {
            return response()->json(['error'=>'Nao foi possivel encontrar o recurso solicitado'], 404);
        }

        if($request->method() === 'PATCH')
        {
            $regrasDinamicas = [];

            foreach($this->marca->rules() as $key => $value)
            {
                if(array_key_exists($key, $request->all()))
                {
                    $regrasDinamicas[$key] = $value;
                }
            }

            $request->validate($regrasDinamicas, $this->marca->feedback());
        }
        else
        {
            $request->validate($this->marca->rules(), $this->marca->feedback());
        }

        if($request->file('imagem'))
        {
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');

            $urn = $imagem->store('imagens/marcas', 'public');

            $marca->fill($request->all());

            $marca->imagem = $urn;
        }
        else
        {
            $marca->fill($request->all());
        }

        $marca->save();

        return response()->json(['success'=>'Marca atualizada com sucesso'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if($marca == null)
        {
            return response()->json(['error'=>'Nao foi possivel encontrar o recurso solicitado'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();

        return response()->json(['success'=>'Marca deletada com sucesso'], 200);
    }
}
