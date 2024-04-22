<?php

namespace Jmrashed\Zkteco\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for Zkteco library integration.
 *
 * This service provider is responsible for registering services related to the Zkteco library
 * within the Laravel application.
 */
class ZktecoServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * This method is called during the Laravel application bootstrap process.
   * It's a good place to perform tasks that don't directly involve registering services with the application container.
   *
   * In this example, the `boot` method is currently empty, but it could be used for tasks like:
   *  - Registering blade directives or view composers for Zkteco related functionalities.
   *  - Publishing configuration files for the Zkteco library.
   *
   * @return void
   */
  public function boot()
  {
    // Code for bootstrapping the service provider (currently empty)
  }

  /**
   * Register services.
   *
   * This method is called when the service provider is registered with the Laravel application.
   * It's the primary method for registering services with the application container.
   *
   * In this example, the `register` method is currently empty, but it could be used for tasks like:
   *  - Registering the Zkteco helper classes or facade as singletons.
   *  - Binding contracts or interfaces to concrete Zkteco library implementations.
   *
   * @return void
   */
  public function register()
  {
    // Code for registering services (currently empty)
  }
}