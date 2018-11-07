<?php

declare(strict_types=1);

namespace SavvyWombat\LaravelTestUtils;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;

/**
 * Trait DisablesExceptionHandling
 *
 * Disable the default exception handler inside the Laravel application to get more helpful feedback during testing.
 * Based on {@link https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da} by Adam Wathan.
 *
 * @package SavvyWombat\LaravelTestUtils
 */
trait DisablesExceptionHandling
{
    /**
     * Prevent repeated calls to disable handling having an affect, or protect from weirdness if handling is enabled
     * before being disabled.
     *
     * @var bool
     */
    protected $exceptionHandlingDisabled = false;

    /**
     * Store the original handler so it can be restored.
     * @var \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected $oldExceptionHandler = null;

    /**
     * Disable exception handling for this test.
     *
     * @return $this
     */
    protected function disableExceptionHandling()
    {
        if (!$this->exceptionHandlingDisabled) {
            $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

            $this->app->instance(ExceptionHandler::class, new class extends Handler{
                public function __construct() {}
                public function report(\Exception $e) {}
                public function render($request, \Exception $e) {
                    throw $e;
                }
            });

            $this->exceptionHandlingDisabled = true;
        }

        return $this;
    }

    /**
     * Enable exception handling for this test.
     *
     * @return $this
     */
    protected function withExceptionHandling()
    {
        if ($this->exceptionHandlingDisabled) {
            $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

            $this->exceptionHandlingDisabled = false;
        }

        return $this;
    }
}