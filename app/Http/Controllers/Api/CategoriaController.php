<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria; // Adiciona model

class CategoriaController extends Controller
{
    // cria index
    public function index(Categoria $categoria, Request $request)
    {
        $categorias = $categoria->getResults($request->name);

        return response()->json($categorias);
    }
}
