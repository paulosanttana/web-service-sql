<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria; // Adiciona model
use App\Http\Requests\ValidaCategoriaFormRequest; // Validação de UPDATE/CREATE

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

    public function show($id)
    {
        $categoria = $this->categoria->find($id);
        if(!$categoria)
            return response()->json(['error' => 'Registro Nao encontrado!'], 404);
        
        return response()->json($categoria);
    }

    // metodo Store, padrão para salvar informação.
    public function store(ValidaCategoriaFormRequest $request)
    {
        $categoria = $this->categoria->create($request->all());

        return response()->json($categoria, 201);
    }

    public function update(Request $request, $id)
    {
        $categoria = $this->categoria->find($id);
        if(!$categoria)
            return response()->json(['error' => 'Registro Nao encontrado!'], 404);

        $categoria->update($request->all());

        return response()->json($categoria, 200);
    }

    public function destroy($id)
    {
        $categoria = $this->categoria->find($id);
        if(!$categoria)
            return response()->json(['error' => 'Registro Nao encontrado!'], 404);

        $categoria->delete();

        return response()->json(['success' => true], 204);
    }

}
