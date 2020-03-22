<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria; // Adiciona model

class CategoriaController extends Controller
{
    // propriedade que recebe o objeto de categoria 
    private $categoria;

    public function __construct(Categoria $categoria)
    {
        $this->categoria = $categoria;
    }


    // cria index
    public function index(Categoria $categoria, Request $request)
    {
        $categorias = $this->categoria->getResults($request->name);

        return response()->json($categorias);
    }

    // metodo Store, padrão para salvar informação.
    public function store(Request $request)
    {
        $categoria = $this->categoria->create($request->all());

        return response()->json($categoria, 201);
    }
}
