<?php

namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use xqus\laraSec\laraSec;

class Scan extends Command {
    protected $signature = 'larasec:scan {--u|update=yes : Update the vulnerability database?}';
    protected $description = 'Run a laraSec scan';

    public function handle() {
      $this->info('-= Starting scan =-');

      if($this->option('update') !== 'no') {
        if ($this->option('update') === 'yes' || $this->confirm('Do you wish to update the vulnerability database first?')) {
          $this->call('larasec:update');
        }
      }

      $laraSec = new laraSec;

      $composerLock = $laraSec->getDependencies();
      if($composerLock === false) {
        $this->error('Unable to open composer.lock');
        return 1;
      }

      $packages = $composerLock['packages'];

      foreach($packages as $package) {
        list($vendor, $project) = explode('/', $package['name']);
        $alerts = $laraSec->getSecurityAlerts($vendor, $project, $package['version']);
        if(sizeof($alerts) > 0) {
          $this->error($package['name']);
          foreach($alerts as $alert) {
            $this->info($package['version'].': '.$alert['title']);
            $this->line($alert['link']);
          }
        }
      }
      $this->comment('Scanned '.sizeof($packages).' packages, found '.sizeof($alerts).' vulnerabilities.');
    }
}
