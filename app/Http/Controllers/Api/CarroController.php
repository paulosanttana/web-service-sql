<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carro;

class CarroController extends Controller
{
    private $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }


    public function index(Carro $carro, Request $request)
    {
        $carros = $this->carro->getResults($request->name);

        return response()->json($carros);
    }

    public function store(Request $request)
    {
        $carro = $this->carro->create($request->all());

        return response()->json($carro, 201);
    }
}
