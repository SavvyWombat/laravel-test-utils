<?php

declare(strict_types=1);

namespace SavvyWombat\LaravelTestUtils;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

trait MocksGuzzle
{
    protected $guzzleStack;

    /**
     * @return \GuzzleHttp\Handler\MockHandler
     */
    protected function guzzle()
    {
        if (is_null($this->guzzleStack)) {
            $this->guzzleStack = new MockHandler();

            $this->app->bind(Client::class, function ($app) {
                return new Client([
                    'handler' => HandlerStack::create($this->guzzleStack),
                ]);
            });
        }

        return $this->guzzleStack;
    }
}