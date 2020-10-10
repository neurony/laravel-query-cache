# Package discontinued! Check out VARBOX.IO instead.

Unfortunately this package is now discontinued.   
Please check out [Varbox](https://varbox.io) (Laravel Admin Panel) for this functionality and much more.

- Buy: [https://varbox.io/buy](https://varbox.io/buy)
- Docs: [https://varbox.io/docs](https://varbox.io/docs)
- Demo: [https://demo.varbox.test/admin](https://demo.varbox.test/admin)
- Repo [https://github.com/VarboxInternational/varbox](https://github.com/VarboxInternational/varbox)

Thank you! 

---

### Cache all "select" queries or only the duplicate ones for a specific Eloquent model

[![Build Status](https://travis-ci.org/Neurony/laravel-query-cache.svg?branch=master)](https://travis-ci.org/Neurony/laravel-query-cache)
[![StyleCI](https://github.styleci.io/repos/177636041/shield?branch=master)](https://github.styleci.io/repos/177636041)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Neurony/laravel-query-cache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Neurony/laravel-query-cache/?branch=master)

- [Overview](#overview)   
- [Installation](#installation)   
- [Usage](#usage)   
- [Extra](#extra)   

### Overview

This package allows you to cache all queries of type `select`, or only just the duplicated ones for an Eloquent model.    
   
> Please note that because cache tagging is used, "file" or "database" cache drivers are incompatible with this package.   
>    
> **Compatible cache stores:** array, redis, apc, memcached   
> **Tested cache stores:** array, redis

### Installation

Install the package via Composer:

```
composer require neurony/laravel-query-cache
```

Publish the config file with:

```
php artisan vendor:publish --provider="Neurony\QueryCache\ServiceProvider" --tag="config"
```

> Please read the `config/query-cache.php` config file comments as it contains extra information

### Usage

##### Step 1

Your Eloquent models should use the `Neurony\QueryCache\Traits\IsCacheable` trait.   

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Neurony\QueryCache\Traits\IsCacheable;

class YourModel extends Model
{
    use IsCacheable;
}
```

##### Step 2

In your `.env` file add the necessary environment variables:

```
# The driver used for storing the cache keys that this package generates.
# This driver can differ from your main Laravel's CACHE_DRIVER.
QUERY_CACHE_DRIVER=redis

# Wether to cache absolutely all queries for the current request.
CACHE_ALL_QUERIES=true

# Wether to cache only the duplicated queries for the current request.
CACHE_DUPLICATE_QUERIES=true
```

Depending on how you set your environment variables, the next time you make `select` queries on that Eloquent model, after the very first run, the queries will be cached.

### Extra

##### Using the `QueryCacheService` class

Please note that the `Neurony\QueryCache\Services\QueryCacheService` class is the actual implementation of the `Neurony\QueryCache\Contracts\QueryCacheServiceContract` interface.   
   
The `Neurony\QueryCache\Services\QueryCacheService` class is bound to the Laravel's IoC as a singleton and aliased as `cache.query`.   
   
With that being said, the recommended way of directly using the `Neurony\QueryCache\Services\QueryCacheService` is:

```php
app('cache.query');

// or

app(QueryCacheServiceContract::class);
```

##### Enable / Disable query caching

You can enable or disable all query caching for your current request, by calling the `enableQueryCache` or `disableQueryCache` methods present on the `Neurony\QueryCache\Services\QueryCacheService` class.   
  
```php
// from her on, no queries will be cached
app('cache.query')->disableQueryCache();

/*
make your queries
the queries up until now won't be cached
*/

// from her on, apply query caching
app('cache.query')->enableQueryCache();

/*
make your queries
this queries will be cached
*/
```

### Credits

- [Andrei Badea](https://github.com/zbiller)
- [All Contributors](../../contributors)

### Security

If you discover any security related issues, please email andrei.badea@neurony.ro instead of using the issue tracker.

### License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
