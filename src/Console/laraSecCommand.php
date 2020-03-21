<?php

namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use xqus\laraSec\laraSec;

class laraSecCommand extends Command {
    protected $signature = 'larasec:scan';
    protected $description = 'Run a laraSec scan';

    public function handle() {
      $laraSec = new laraSec;

      $composerLock = $laraSec->getDependencies();

      $packages = $composerLock['packages'];

      foreach($packages as $package) {
        list($vendor, $project) = explode('/', $package['name']);
        $alerts = $laraSec->getSecurityAlerts($vendor, $project,'7.0.0');
        if(sizeof($alerts) > 0) {
          $this->error($package['name']);
          foreach($alerts as $alert) {
            $this->info($package['version'].': '.$alert['title']);
            $this->line($alert['link']);
          }
        }
      }
    }
}
