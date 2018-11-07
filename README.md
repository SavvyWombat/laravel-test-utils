# Laravel test utilities

Utilities and helpers for testing Laravel based applications

```composer require --dev savvywombat/laravel-test-utils```

## Database factory helpers

Inspired by a [Jeffrey Way Laracast](https://laracasts.com/series/lets-build-a-forum-with-laravel/episodes/10).

```php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use SavvyWombat\LaravelTestUtils\DatabaseFactories;
use Tests\TestCase;

class MyTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseFactories;
    
    /** @test */
    public function it_uses_a_factory_to_create_a_model_and_saves_to_database()
    {
        $model = $this->create('App\Model');
        
        $this->assertInstanceOf('App\Model', $model);
        $this->assertDatabaseHas('models', [
            'field' => $model->field
        ]);
    }
    
    /** @test */
    public function it_uses_a_factory_to_create_a_model_without_saving_to_database()
    {
        $model = $this->make('App\Model');
                
        $this->assertInstanceOf('App\Model', $model);
        $this->assertDatabaseMissing('models', [
            'field' => $model->field
        ]);
    }
}
```

### create()

Create one or more models using the Laravel database factory.

```php
$model = $this->create('App\Model');
// Creates requested model, inserting the data into the database.

$model = $this->create('App\Model', ['field' => 'value']);
// Creates requested model, overriding the factory default for the specific 'fields'.

$collection = $this->create('App\Model', [], 2);
// Creates multiple models, returning as a Collection
```

### make()

Make one or more models using the Laravel database factory.

```php
$model = $this->make('App\Model');
// Creates requested model, without inserting the data into the database.

$model = $this->make('App\Model', ['field' => 'value']);
// Creates requested model, overriding the factory default for the specific 'fields'.

$collection = $this->make('App\Model', [], 2);
// Creates multiple models, returning as a Collection
```

## Disable exception handling

Disables the Laravel exception handler to prevent it spewing HTML into the console. Using this trait allows you disable
the exception handler, causing the actual exception to be returned to the test - or re-enable it, allowing you to test
HTTP status codes.

It is possible to disable/re-enable on a test by test basis, or you can put the call to disable exception handling in
your test case's `setUp()` method.

Based on a [gist from Adam Wathan](https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da).

```php
namespace Tests\Feature;

use SavvyWombat\LaravelTestUtils\DisablesExceptionHandling;
use Tests\TestCase;

class MyTest extends TestCase
{
    use DisablesExceptionHandling;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->disableExceptionHandling();
    }
    
    /** @test */
    public function it_is_not_testing_response_status()
    {
        $this->get('/good-url')
            ->assertSee('some text');
    }
    
    /** @test */
    public function it_reenables_exception()
    {
        $this->withExceptionHandling()
            ->get('/bad-url')
            ->assertStatus(404);
    }
}
```

## Support

If you are having general issues with this repository, please contact us via
the [SavvyWombat](https://savvywombat.com/contact) website.

Please report issues using the [GitHub issue tracker](https://github.com/SavvyWombat/laravel-test-utils/issues). You are also welcome to fork the repository and submit a pull request.

If you're using this repository, we'd love to hear your thoughts. Thanks!

## Licence

This package is licensed under [The MIT License (MIT)](https://github.com/SavvyWombat/laravel-test-utils/blob/master/LICENSE).
