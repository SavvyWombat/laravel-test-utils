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

## Database casting helpers

```php
use SavvyWombat\LaravelTestUtils\DBCast
```

### DBCast::toJson

Use this helper when asserting against the database (such as with assertDatabaseHas or assertDatabaseMissing) to 
cast an array or json string to a JSON datatype.

```php
$this->assertDatabaseHas('vehicles', [
    'id' => 1,
    'manufacturer' => 'Toyford',
    'model' => 'Llama',
    'attributes' => DBCast::toJson([
        'color' => 'indigo green',
        'engine' => '2 litres 4-cylinder',
        'gearbox' => '6-speed manual',
        'doors' => '5',
    ]),
]);
```

## Mock guzzle

This trait assumes that you are using Laravel's IoC to inject the Guzzle client into your code.

```php
namespace Tests\Feature;

use GuzzleHttp\Psr7\Response;
use SavvyWombat\LaravelTestUtils\MocksGuzzle;
use Tests\TestCase;

class MyTest extends TestCase
{
    use MocksGuzzle;
    
    /** @test */
    public function it_reacts_to_google_recaptcha()
    {
        $this->guzzle() // this is guzzle's MockHandler class, so we can append responses here
            ->append(new Response(200, [], json_encode(['success' => 'true'])
            ->append(new Response(200, [], json_encode(['success' => 'false']);
            
        $this->post('/some-url', ['g-recaptcha-token' => 'blah'])
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertRedirect('/success');
        
        $this->post('/some-url', ['g-recaptcha-token' => 'blah'])
            ->assertStatus(302)
            ->assertSessionHasErrors('g-recaptcha-token')
            ->assertRedirect('/some-url');
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
