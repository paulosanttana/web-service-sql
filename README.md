## Configurações

Instalar Laravel
```bash
composer create-project --prefer-dist laravel/laravel laravel-web-service "6.*"
```

Configurar Time-Zone 
```php
//config/app.php

'timezone' => 'America/Sao_Paulo',
```

Configurar nome da plicação e URL da aplicação `.env`.
```php
APP_NAME=Laravel

APP_URL=http://localhost
```

## Migration

Criar `migration` de Categoria (se colocar parametro -mc já cria o Model e Controller).
```bash
php artisan make:model Models\\Categoria -m
```

## Configura Migrate

Adicionado novo campo `name`
```php
// database\migrations\CreateCategoriasTable 

public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique(); //Adicionado novo campo NAME.
            $table->timestamps();
        });
    }
```


## Configura conexão
```php
// .env
DB_CONNECTION=sqlsrv
DB_HOST="DESKTOP-890UMAI"  
DB_PORT=null
DB_DATABASE=LARAVEL_API_SQL
DB_USERNAME=sa
DB_PASSWORD=sql@2020
```

```php
// config\database.php

 'default' => env('DB_CONNECTION', 'sqlsrv'),

'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'DESKTOP-890UMAI'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
```


## Controller Simples

Criar controller de API (se adicionar parametro --resource cria todos os metodos)
```bash
php artisan make:controller Api\\CategoriaController
```

## Listando categorias
```php
// CategoriaController

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria; // Adiciona model

class CategoriaController extends Controller
{
    // cria index
    public function index(Categoria $categoria)
    {
        $categorias = $categoria->all();

        return response()->json($categorias);
    }
}
```

## Rotas

```php
<?php
// routes/api.php

Route::get('categorias', 'Api\CategoriaController@index');
```


## Faça INSERT categoria teste
```sql
-- INSERT CATEGORIA teste
INSERT INTO LARAVEL_API_SQL..categorias (name) VALUES ('teste')
```

Faça o teste no Postman (http://127.0.0.1:8000/api/categorias)

```json
[
    {
        "id": 1,
        "name": "teste",
        "created_at": null,
        "updated_at": null
    }
]
```

## Listar por filtro (alterar select para filtrar por nome usando like)
```php
// CategoriaController

// $categorias = $categoria->all();
        $categorias = $categoria->where('name', 'LIKE', "%{$request->name}%")->get();
```

No Postman, em Query Params passe o parametro

## Passado consulta para o Model
Cria um método `getResults` para fazer o filtro através do parametro `$name`.
```php
// App\Models\Categoria
class Categoria extends Model
{
    public function getResults($name)
    {
        return $this->where('name', 'LIKE', "%{$name}%")->get();
    }
}

```

No controller passe o parametro `name` da requisição para o método `getResults` do model.
```php
class CategoriaController extends Controller
{
    // cria index
    public function index(Categoria $categoria, Request $request)
    {
        $categorias = $categoria->getResults($request->name);

        return response()->json($categorias);
    }
}
```

Caso não passe parametro faça uma condição.
```php
// App\Models\Categoria
class Categoria extends Model
{
    public function getResults($name = null)
    {
        // Parametro `$name = null` indica que não e um valor obrigatório informar.

        // Se valor for igual a null, retorna tudo.
        if (!$name)
            return $this->get();

        // Se tiver parametro
        return $this->where('name', 'LIKE', "%{$name}%")->get();
    }
}

```






