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


