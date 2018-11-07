<?php

declare(strict_types=1);

namespace SavvyWombat\LaravelTestUtils;

trait DatabaseFactories
{
    /**
     * Create and store into the database one or more models using the Laravel database factory.
     *
     * Inspired by a Jeffrey Way Laracast.
     *
     * @see https://laracasts.com/series/lets-build-a-forum-with-laravel/episodes/10
     *
     * @param string $class The class name for the model being created.
     * @param array $attributes Optional. An array of attributes to inject into the new records, overriding the factory defaults.
     * @param int $number Optional. The number of records to create.
     * @return mixed The requested model, or a collection of them if more than one record is being created.
     */
    public function create(string $class, array $attributes = [], int $number = 1)
    {
        // the factory function always returns a collection if the number parameter is set
        if ($number === 1) {
            return factory($class)->create($attributes);
        } else {
            return factory($class, $number)->create($attributes);
        }
    }

    /**
     * Create one or more models using the Laravel database factory, without storing the data into the database.
     *
     * Inspired by a Jeffrey Way Laracast.
     *
     * @see https://laracasts.com/series/lets-build-a-forum-with-laravel/episodes/10
     *
     * @param string $class The class name for the model being created.
     * @param array $attributes Optional. An array of attributes to inject into the new records, overriding the factory defaults.
     * @param int $number Optional. The number of models to make.
     * @return mixed The requested model, or a collection of them if more than one is being made.
     */
    public function make(string $class, array $attributes = [], int $number = 1)
    {
        // the factory function always returns a collection if the number parameter is set
        if ($number === 1) {
            return factory($class)->make($attributes);
        } else {
            return factory($class, $number)->make($attributes);
        }
    }
}