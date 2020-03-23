<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    protected $fillable = ['name', 'cor'];

    public function getResults($name = null)
    {
        if (!$name)
            return $this->get();
            
        return $this->where('name', 'LIKE', "%{$name}%")->get();
    }
}
