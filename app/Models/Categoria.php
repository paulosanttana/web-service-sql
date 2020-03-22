<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['name'];

    public function getResults($name)
    {
        return $this->where('name', 'LIKE', "%{$name}%")->get();
    }
}
