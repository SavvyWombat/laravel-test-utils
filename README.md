# Laravel test utilities

Utilities and helpers for testing Laravel based applications

```composer require --dev savvywombat/laravel-test-utils```

## Database factory helpers

Inspired by a [Jeffrey Way Laracast](https://laracasts.com/series/lets-build-a-forum-with-laravel/episodes/10).

### create()

Create one or more models using the Laravel database factory.

```php
$model = create('App\Model');
// Creates requested model, inserting the data into the database.

$model = create('App\Model', ['field' => 'value']);
// Creates requested model, overriding the factory default for the specific 'fields'.

$collection = create('App\Model', [], 2);
// Creates multiple models, returning as a Collection
```

### make()

Make one or more models using the Laravel database factory.

```php
$model = create('App\Model');
// Creates requested model, without inserting the data into the database.

$model = create('App\Model', ['field' => 'value']);
// Creates requested model, overriding the factory default for the specific 'fields'.

$collection = create('App\Model', [], 2);
// Creates multiple models, returning as a Collection
```

## Support

If you are having general issues with this repository, please contact us via
the [SavvyWombat](https://savvywombat.com/contact) website.

Please report issues using the [GitHub issue tracker](https://github.com/SavvyWombat/laravel-test-utils/issues). You are also welcome to fork the repository and submit a pull request.

If you're using this repository, we'd love to hear your thoughts. Thanks!

## Licence

This package is licensed under [The MIT License (MIT)](https://github.com/SavvyWombat/laravel-test-utils/blob/master/LICENSE).
