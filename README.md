# TokonYa - Laravel Ecommerce Boilerplate

TokonYa is backed for faster development. It means we not use bloatware code.

## PSR2 Standart

```bash
php php-cs-fixer.phar fix -vvv --dry-run --show-progress=dots
```

## ALL FEATURES

- We using Oauth 2.0 for authorization using [Passport](https://laravel.com/docs/7.x/passport).
- We choice [UUID v4](https://en.wikipedia.org/wiki/Universally_unique_identifier) instead Autoincrement model.
- REST API only (except mail verification)
- [Pagination](https://laravel.com/docs/7.x/pagination)
- [Eloquent API Resources](https://laravel.com/docs/7.x/eloquent-resources)
- [SoftDeletes Model](https://laravel.com/docs/master/eloquent#soft-deleting)
- [Standard API Response](https://google.github.io/styleguide/jsoncstyleguide.xml)

### CUSTOMER FEATURES

- [x] Customer login
- [x] Customer registration
- [x] Customer verify (by email)
- [x] Customer can update profile (avatar, address, gender)

### MERCHANT FEATURES

- [x] Merchant login
- [ ] Merchant registration
- [ ] Merchant verify (by email)
- [ ] Merchant create product

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

## RUNNING ON DOCKER CONTAINER

Before continue, please stop your (apache / nginx), postgres on your host machine.

### STEP 1 DOCKER

```bash
cp .env.docker.example .env.docker
```

### STEP 2 DOCKER

```bash
./docker-build.sh
```

OR (WINDOWS OS)

```bash
docker network create tokonya-net
docker volume create pg-tokonya
docker volume create redis-vol
docker-compose up -d --build
```

### STEP 3 DOCKER

```bash
docker-compose exec tokonya-app php artisan migrate:fresh --seed --force
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

### CUSTOMER SHOW PROFILE

```bash
curl -X GET \
  http://localhost:8000/api/customer/profile \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer {{YOUR_ACCESS_TOKEN}}' \
  -H 'Content-Type: application/json' \
  -H 'cache-control: no-cache'
```

### CUSTOMER UPDATE PROFILE

```bash
curl -X POST \
  http://localhost:8000/api/customer/update \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer {{YOUR_ACCESS_TOKEN}}' \
  -H 'content-type: multipart/form-data' \
  -H 'cache-control: no-cache' \
  -F 'customer_address=Your address' \
  -F 'gender=male' \
  -F 'image=@/home/odenktools/03b1c1afcc4a00f2be.png'
```

### REGISTER CUSTOMER AS MERCHANT

```bash
curl -X POST \
  http://localhost:8000/api/customer/register-merchant \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer {{YOUR_ACCESS_TOKEN}}' \
  -H 'Content-Type: application/json' \
  -H 'cache-control: no-cache' \
  -d '{
	"merchant_name": "Coffe Goodds",
	"merchant_address": "Chicago, IL 60605"
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

#### ROLE

```php
@role('owner')
    I am a owner!
@else
    I am not a owner...
@endrole
```


#### Dependencies

* [Laravel Framework 7.x](https://laravel.com/docs/7.x)
* [intervention image](http://image.intervention.io)

# LICENSE

MIT License

Copyright (c) 2021 odenktools

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
