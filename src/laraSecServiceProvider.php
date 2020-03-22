<?php
namespace xqus\laraSec;

use Illuminate\Support\ServiceProvider;
use xqus\laraSec\Console\Scan;
use xqus\laraSec\Console\Update;

class laraSecServiceProvider extends ServiceProvider {

  public function register() {

  }

  public function boot() {
    if ($this->app->runningInConsole()) {

    $this->commands([
        Scan::class,
        Update::class,
    ]);
  }
  }
}
