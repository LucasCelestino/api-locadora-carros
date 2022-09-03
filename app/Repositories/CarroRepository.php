<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class CarroRepository
{

    private $model;

    public function __construct(Model $modelParam)
    {
        $this->model = $modelParam;
    }

    public function selectWith()
    {
        $this->model = $this->model->with('modelo');
    }

    public function selectAtributosRegistros($atributos)
    {
        $this->model = $this->model->selectRaw($atributos)->with('modelo');
    }

    public function filtro($filtros)
    { 
        $condicoes = explode(":", $filtros);

        $this->model =  $this->model->where($condicoes[0], $condicoes[1], $condicoes[2]);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
}