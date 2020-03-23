<?php
namespace xqus\laraSec;

use Illuminate\Support\ServiceProvider;
use xqus\laraSec\Console\Scan;
use xqus\laraSec\Console\Update;

class laraSecServiceProvider extends ServiceProvider {

  public function register() {
    $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'larasec');
  }

  public function boot() {
    if ($this->app->runningInConsole()) {
      $this->publishes([
       __DIR__.'/../config/config.php' => config_path('larasec.php'),
     ], 'config');

      $this->commands([
        Scan::class,
        Update::class,
      ]);
    }
  }
}
