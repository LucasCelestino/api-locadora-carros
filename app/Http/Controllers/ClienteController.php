<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;

class ClienteController extends Controller
{
    private Cliente $cliente;

    public function __construct(Cliente $clienteParam)
    {
        $this->cliente = $clienteParam;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = $this->cliente->all();

        return response()->json($clientes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClienteRequest $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());

        $cliente = $this->cliente->create([
            'nome'=>$request->get('nome')
        ]);

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClienteRequest $request, int $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        $request->validate($this->cliente->rules(), $this->cliente->feedback());

        $cliente->fill($request->all());

        $cliente->save();

        return response()->json(['success'=>'Cliente atualizado com sucesso'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente == null)
        {
            return response()->json(['error'=>'Não foi possível encontrar o recurso solicitado'], 404);
        }

        $cliente->delete();

        return response()->json(['success'=>'Cliente deletado com sucesso'], 200);
    }
}
