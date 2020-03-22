## Passo 1

Instalar Laravel
```bash
composer create-project --prefer-dist laravel/laravel laravel-web-service "6.*"
```

Configurar Time-Zone 
```php
//config/app.php

'timezone' => 'America/Sao_Paulo',
```

Configurar nome da plicação e URL da aplicação `.env`
`php
APP_NAME=Laravel

APP_URL=http://localhost
`

Criar `migration` de Categoria (se colocar parametro -mc já cria o Model e Controller).
``bash
php artisan make:model Models\\Categoria -m
``

