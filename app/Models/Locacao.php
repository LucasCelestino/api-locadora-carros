<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';

    protected $fillable = ['cliente_id','carro_id', 'data_inicio_periodo', 'data_final_previsto_periodo','data_final_realizado_periodo','valor_diaria','km_inicial','km_final'];

    public function rules()
    {
        return [
            'cliente_id'=>'exists:clientes,id',
            'carro_id'=>'exists:carros,id',
            'data_inicio_periodo'=>'required',
            'data_final_previsto_periodo'=>'required',
            'data_final_realizado_periodo',
            'valor_diaria'=>'required',
            'km_inicial'=>'required',
            'km_final'
        ];
    }

    public function feedback()
    {
        return [
            'required'=>'O campo :attribute é obrigatório',
            'cliente_id.exists'=>'O cliente precisa ser válido para fazer uma locação',
            'carro_id.exists'=>'O carro precisa ser válido para fazer uma locação'
        ];
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id');
    }

    public function carro()
    {
        return $this->belongsTo('App\Models\Carro', 'carro_id');
    }
}
