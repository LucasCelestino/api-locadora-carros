<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['placa','disponivel','km','modelo_id'];

    public function rules()
    {
        return [
            'placa'=>'required|unique:carros,id,'.$this->id.'',
            'disponivel'=>'required|boolean',
            'km'=>'required',
            'modelo_id'=>'exists:modelos,id'
        ];
    }

    public function feedback()
    {
        return [
            'required'=>'O campo :attribute é obrigatório',
            'placa.unique'=>'O número da placa já existe',
            'boolean'=>'O campo :attribute só aceita valores true ou false'
        ];
    }

    public function modelo()
    {
        return $this->belongsTo('App\Models\Modelo');
    }
}
