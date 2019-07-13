# Larammerce Validation

A modern validation package for laravel apps, based on laravel validation and `larammerce/annotation-parser` package.

## Installation

```bash
composer require larammerce/validation
```

## Usage

- First you need to publish configuration file for this project.

```bash
php artisan vendor:publish --provider="Larammerce\Validation\ValidationService\Provider"
```

- Then customise the config file if needed.

```php
return [
    "annotation_name" => "rules", // the rules will be read from @rules annotation.
    "dynamic_rules_key" => "dynamic_rules" // dynamic rules (described in docs) will be read from dynamic_rules key.
];
```

- Add `Larammerce\Validation\Middleware\RulesMiddleware::class` to your route or group of routes as middleware directly, or you can add it to `$routeMiddleware` in `/app/Http/Kernel.php` file.

```php
//Kernel.php file.
protected $routeMiddleware = [
    //...
    "rules" => Larammerce\Validation\Middleware\RulesMiddleware::class
];
```

```php
//routes/web.php | routes/api.php file.

//single route
Route::get("admin/profile", function () {
    //
})->middleware("rules");


//group route
Route::group(["middleware" => ["rules"]], function () {
    //add other routes.
});
```
