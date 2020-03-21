<?php
namespace xqus\laraSec;

use Illuminate\Support\ServiceProvider;
use xqus\laraSec\Console\laraSecCommand;

class laraSecServiceProvider extends ServiceProvider {

  public function register() {

  }

  public function boot() {
    if ($this->app->runningInConsole()) {

    $this->commands([
        laraSecCommand::class,
    ]);
  }
  }
}
