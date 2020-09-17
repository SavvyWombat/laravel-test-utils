# Laravel test utilities

Utilities and helpers for testing Laravel based applications

```composer require --dev savvywombat/laravel-test-utils```

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

### DBCast::toTimestamp

User this helper to assert Datetime or Carbon instances against timestamps in the database.

```php
$trip = Trip::factory()->create();

Carbon::setTestNow(Carbon::now());

$response = $this->get('/start-trip');

$response->assertStatus(200)
    ->assertSee('Trip started');

$this->assertDatabaseHas('trips', [
    'id' => $trip->id,
    'trip_started_at' => DBCast::toTimestamp(Carbon::now()),
]);

Carbon::setTestNow();
```

## Mock guzzle

This trait assumes that you are using Laravel's IoC to inject the Guzzle client into your code.

Say we're testing a contact form with a Google Recaptcha:

```php
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request, Client $client)
    {
        // Validate ReCaptcha
        $response = $this->client->post(config('recaptcha.url'), [
            'query' => [
                'secret' => config('recaptcha.secret'),
                'response' => $request->input(['g-recaptcha-token'], ''),
            ]
        ]);

        if (json_decode($response->getBody())->success) {
            redirect()->route('contact::success');
        } else {
            redirect()->route('contact::form')->withErrors(['g-recaptcha-token' => 'Please confirm you are a human!']);
    }
}
```

```php
namespace Tests\Feature;

use GuzzleHttp\Psr7\Response;
use SavvyWombat\LaravelTestUtils\MocksGuzzle;
use Tests\TestCase;

class MyTest extends TestCase
{
    use MocksGuzzle;
    
    /** @test */
    public function it_reacts_appropriately_to_recaptcha_success()
    {
        $this->guzzle() // this is guzzle's MockHandler class
            ->append(new Response(200, [], json_encode(['success' => 'true'])));
            
        $this->post('/some-url', ['message' => 'some message', 'g-recaptcha-token' => 'blah'])
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertRedirect('/success');
    }
    
    /** @test */
    public function it_errors_on_recaptcha_failure()
    {   
        $this->guzzle() // this is guzzle's MockHandler class
            ->append(new Response(200, [], json_encode(['success' => 'false'])));
                
        $this->post('/some-url', ['message' => 'some message', 'g-recaptcha-token' => 'blah'])
            ->assertStatus(302)
            ->assertSessionHasErrors('g-recaptcha-token')
            ->assertRedirect('/some-url');
    }
}
```

If your controller or other code needs to make more than one request to an API, you can chain responses to the guzzle handler:

```php
$this->guzzle()
    ->append(new Response(200, [], json_encode(['invoices' => ['id' => '1245', 'id' => '1247']])))
    ->append(new Response(404));
```

### Caveats

Currently, the guzzle mock doesn't assert or check anything regarding what requests you are making - 
it's just a simple way to test your code's reaction to a particular response without having to actually transmit a 
request to the remote API.

## Support

If you are having general issues with this repository, please contact us via
the [SavvyWombat](https://savvywombat.com/contact) website.

Please report issues using the [GitHub issue tracker](https://github.com/SavvyWombat/laravel-test-utils/issues). You are also welcome to fork the repository and submit a pull request.

If you're using this repository, we'd love to hear your thoughts. Thanks!

## Licence

This package is licensed under [The MIT License (MIT)](https://github.com/SavvyWombat/laravel-test-utils/blob/master/LICENSE).
