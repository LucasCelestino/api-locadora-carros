<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Http\Requests\StoreLocacaoRequest;
use App\Http\Requests\UpdateLocacaoRequest;

class LocacaoController extends Controller
{
    private Locacao $locacao;

    public function __construct(Locacao $locacaoParam)
    {
        $this->locacao = $locacaoParam;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locacoes = $this->locacao->all();

        return response()->json($locacoes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocacaoRequest $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedback());

        $locacao = $this->locacao->create([
            'cliente_id'=>$request->get('cliente_id'),
            'carro_id'=>$request->get('carro_id'),
            'data_inicio_periodo'=>$request->get('data_inicio_periodo'),
            'data_final_previsto_periodo'=>$request->get('data_final_previsto_periodo'),
            'data_final_realizado_periodo'=>$request->get('data_final_realizado_periodo'),
            'valor_diaria'=>$request->get('valor_diaria'),
            'km_inicial'=>$request->get('km_inicial'),
            'km_final'=>$request->get('km_final')
        ]);

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $locacao = $this->locacao->with('cliente')->with('carro')->find($id);

        if($locacao == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocacaoRequest $request, int $id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        if($request->method() === 'PATCH')
        {
            $regrasDinamicas = [];

            foreach($this->locacao->rules() as $key => $value)
            {
                if(array_key_exists($key, $request->all()))
                {
                    $regrasDinamicas[$key] = $value;
                }
            }

            $request->validate($regrasDinamicas, $this->locacao->feedback());
        }
        else
        {
            $request->validate($this->locacao->rules(), $this->locacao->feedback());
        }

        $locacao->fill($request->all());

        $locacao->save();

        return response()->json(['success'=>'Locacao atualizada com sucesso'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        $locacao->delete();

        return response()->json(['success'=>'Locacao deletada com sucesso'], 200);
    }
}
