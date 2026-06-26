# SwagLite

Lightweight API documentation for Laravel.

## Features

- Automatic route discovery
- Interactive API documentation
- Execute API requests directly from the browser
- Path parameters (`/users/{id}`)
- JSON request bodies
- GET, POST, PUT, PATCH and DELETE support
- Search endpoints instantly
- Dark mode support
- Attribute-based documentation
- Lightweight and zero external dependencies

## Installation

```bash
composer require guljahang/swaglite
```

Publish the package assets:

```bash
php artisan vendor:publish --tag=swaglite-assets
```

## Usage

### Response Documentation

```php
#[SwagResponse(
    status: 200,
    description: 'Success'
)]
public function index()
{
    return User::all();
}
```

### Path Parameters

```php
#[SwagParameter(
    name: 'id',
    in: 'path',
    required: true,
    example: 1
)]
public function show($id)
{
    return User::findOrFail($id);
}
```

### Request Body

```php
#[SwagParameter(
    name: 'name',
    in: 'body',
    required: true
)]
#[SwagParameter(
    name: 'email',
    in: 'body',
    required: true
)]
public function store(Request $request)
{
    return $request->all();
}
```

## Supported Methods

- GET
- POST
- PUT
- PATCH
- DELETE

## License

MIT