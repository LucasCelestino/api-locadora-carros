<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';

    protected $fillable = ['nome','imagem'];

    public function rules()
    {
        return [
            'nome'=>'required|unique:marcas,nome,'.$this->id.'',
            'imagem'=>'required|file'
        ];
    }

    public function feedback()
    {
        return [
            'required'=>'O campo :attribute é obrigatório',
            'nome.unique'=>'O nome da marca já existe',
            'imagem.mimes'=>'O arquivo precisa ser uma imagem (.png, .jpg ou.jpeg)'
        ];
    }

    public function modelos()
    {
        return $this->hasMany('App\Models\Modelo');
    }
}
