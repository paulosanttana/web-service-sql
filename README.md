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

Executar `php artisan migrate` para criar a tabela


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


## Faça INSERT categoria teste diretamente pelo SQL Server
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

## LISTAR por filtro (alterar select para filtrar por nome usando like)
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
    protected $fillable = ['name'];

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
    protected $fillable = ['name'];

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

## CADASTRAR Categoria

Para não ficar repetindo injeção da model `Categoria $categoria` nos metodos, crie um construtor. Cria o metodo `Store()` para fazer o insert da nova categoria.

```php
class CategoriaController extends Controller
{
    // propriedade que recebe o objeto de categoria 
    private $categoria;

    public function __construct(Categoria $categoria)
    {
        $this->categoria = $categoria;
    }

    // cria index
    public function index(Request $request)
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
```

Inserir rota para `store` de categoria com o verbo `POST`

```php
// routes\api.php

Route::get('categorias', 'Api\CategoriaController@index');
Route::post('categorias', 'Api\CategoriaController@store');
```

Insert através do Postman, altere o tipo da requisição para `POST` e passa parametros usando a url `http://127.0.0.1:8000/api/categorias`


## EDITAR Categoria

Criar método `update()`
```php
    public function update(Request $request, $id)
    {
        $categoria = $this->categoria->find($id);
        if(!$categoria)
            return response()->json(['error' => 'Registro Nao encontrado!'], 404);

        $categoria->update($request->all());

        return response()->json($categoria, 200);
    }
```
Define rota para update
```php
Route::put('categorias/{id}', 'Api\CategoriaController@update');
```


## Validação Cadastro API com Form Request

Execute para criar arquivo FormRequest de validação
```bash
php artisan make:request ValidaCategoriaFormRequest
```

```php
// App\Http\Requests\ValidaCategoriaFormRequest

public function authorize()
{
    return true; //passe de false para true.
}
...
public function rules()
{
    return [
        'name' => 'required|min:3|max:50|unique:categorias', // Regras de validação
    ];
}
```

Adiciona use para o arquivo e substitua o parametro `Request` no metodo Store() para `use App\Http\Requests\ValidaCategoriaFormRequest; // Validação de UPDATE/CREATE`
```php
use App\Http\Requests\use App\Http\Requests\ValidaCategoriaFormRequest; // Validação de UPDATE/CREATE; // Validação de UPDATE/CREATE
...

// metodo Store, padrão para salvar informação.
public function store(ValidaCategoriaFormRequest $request)
{
    $categoria = $this->categoria->create($request->all());

    return response()->json($categoria, 201);
}
```

Faça o teste pelo Postman, passe os parametros na aba `Headers`, requisição do tipo `AJAX`

> Key: content-Type           Value: application/json
> Key: X-Requested-With       Value: XMLHttpRequest

é na aba Params passe o campo com seu novo valor

> Key: name         Value: Novo_Nome


## DELETAR   Categoria

Adicione metodo `delete`

```php
public function delete($id)
{
    $categoria = $this->categoria->find($id);
    if(!$categoria)
        return response()->json(['error' => 'Registro Nao encontrado!'], 404);

    $categoria->delete();

    return response()->json(['success' => true], 204);
}
```

Cria Rota do tipo `delete`
```php
Route::delete('categorias/{id}', 'Api\CategoriaController@delete');
```


Rotas utilizada
> http://127.0.0.1:8000/api/categorias      // SELECT

> http://127.0.0.1:8000/api/categorias?name=tes_2       // INSERT

> http://127.0.0.1:8000/api/categorias/3?name=UPDATEE       // UPDATE

> http://127.0.0.1:8000/api/carros?name=Sil     // SELECT COM PARAMETRO

> http://127.0.0.1:8000/api/categorias/8        // DELETE