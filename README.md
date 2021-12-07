# TokonYa - Laravel Ecommerce Boilerplate

TokonYa is backed for faster development. It means we don't use bloatware code.

## ALL FEATURES

- We using Oauth 2.0 for authorization using [Passport](https://laravel.com/docs/7.x/passport).
- REST API only
- Pagination
- [Eloquent API Resources](https://laravel.com/docs/7.x/eloquent-resources)
- SoftDeletes
- Standart API Response

### CUSTOMER FEATURES

-[x] Customer registration
-[x] Customer email validation
-[x] Customer login

### MERCHANT FEATURES

-[ ] Merchant registration
-[ ] Merchant email validation
-[x] Merchant login

## HOW TO INSTALL

```bash
php composer2.phar install
```

Create some database

```
cp .env.example .env
```

Setup your database environment

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
    "username" : "alexandria@gmail.com",
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
    "username" : "johndoe@gmail.com",
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
* [intervention image](http://image.intervention.io)

# LICENSE

MIT License

Copyright (c) 2017 odenktools

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
