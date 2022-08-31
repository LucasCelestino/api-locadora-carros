<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id','nome','imagem','numero_portas','lugares','air_bag','abs'];

    public function rules()
    {
        return [
            'marca_id'=>'exists:marcas,id',
            'nome'=>'required|unique:marcas,nome,'.$this->id.'',
            'imagem'=>'required|file',
            'numero_portas'=>'required|integer',
            'lugares'=>'required|integer',
            'air_bag'=>'required',
            'abs'=>'required'
        ];
    }

    public function feedback()
    {
        return [
            'required'=>'O campo :attribute é obrigatório',
            'nome.unique'=>'O nome da marca já existe',
            'imagem.mimes'=>'O arquivo precisa ser uma imagem (.png, .jpg ou.jpeg)',
            'boolean'=>'O campo :attribute só aceita valores true ou false'
        ];
    }

    public function marca()
    {
        return $this->belongsTo('App\Models\Marca', 'marca_id');
    }
}
