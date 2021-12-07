# HOW TO INSTALL

```bash
php composer2.phar install
```

Create database mysql

```sql
DROP DATABASE lv_commerce;

CREATE DATABASE lv_commerce
CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;
```

```
cp .env.example .env
```

```bash
php artisan passport:keys --force
```

```bash
php artisan migrate:fresh --seed
```

## CREDENTIAL INFORMATION

### MERCHANT LOGIN

```bash
curl -X POST \
  http://localhost:8000/api/merchant/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -H 'cache-control: no-cache' \
  -d '{
    "username" : "odenktools@gmail.com",
    "password" : "hey1234",
    "client_id" : 3,
    "client_secret" : "imJrfnJAHPtCre1E4SriBxLZEjDfkFraKLB0aJMi",
    "grant_type" : "password"
}'
```

```bash
curl -X POST \
  http://localhost:8000/api/merchant/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -H 'cache-control: no-cache' \
  -d '{
    "username" : "balabala@gmail.com",
    "password" : "hey1234",
    "client_id" : 3,
    "client_secret" : "imJrfnJAHPtCre1E4SriBxLZEjDfkFraKLB0aJMi",
    "grant_type" : "password"
}'
```

```bash
curl -X POST \
  http://localhost:8000/api/merchant/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -H 'cache-control: no-cache' \
  -d '{
    "username" : "iku@gmail.com",
    "password" : "hey1234",
    "client_id" : 3,
    "client_secret" : "imJrfnJAHPtCre1E4SriBxLZEjDfkFraKLB0aJMi",
    "grant_type" : "password"
}'
```

### CUSTOMER LOGIN

```bash
curl -X POST \
  http://localhost:8000/api/merchant/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -H 'cache-control: no-cache' \
  -d '{
    "username" : "alamsyah@gmail.com",
    "password" : "hey1234",
    "client_id" : 4,
    "client_secret" : "imJrfnJAHPtCre1E4SriBxLZfjDfkFraKLB0aJMi",
    "grant_type" : "password"
}'
```

```bash
curl -X POST \
  http://localhost:8000/api/merchant/login \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -H 'cache-control: no-cache' \
  -d '{
    "username" : "suparnoalex@gmail.com",
    "password" : "hey1234",
    "client_id" : 4,
    "client_secret" : "imJrfnJAHPtCre1E4SriBxLZfjDfkFraKLB0aJMi",
    "grant_type" : "password"
}'
```

#### INSTALLING IDE HELPERS (OPTIONAL)

```bash
composer require --dev barryvdh/laravel-ide-helper
```

#### Automatic PHPDoc generation for Laravel Facades

```bash
php artisan ide-helper:generate
php artisan clear-compiled
```

#### Automatic PHPDoc generation for Laravel Model

```bash
php artisan ide-helper:generate
php artisan clear-compiled
```

#### Dependencies

* [Laravel Framework 7.x](https://laravel.com/docs/7.x)
* [Unirest](https://github.com/Kong/unirest-php)
* [intervention image](http://image.intervention.io)
