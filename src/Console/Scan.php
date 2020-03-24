<?php
namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use xqus\laraSec\laraSec;
use xqus\laraSec\Packagist;

class Scan extends Command {
    protected $signature = 'larasec:scan {--u|update=ask : Update the vulnerability database?}';
    protected $description = 'Run a laraSec scan';

    public function handle() {
      $this->info('-= Starting scan =-');

      if($this->option('update') !== 'no') {
        if ($this->option('update') === 'yes' || $this->confirm('Do you wish to update the vulnerability database first?')) {
          $this->call('larasec:update');
        }
      }

      $laraSec   = new laraSec;
      $Packagist = new Packagist;

      $composerLock = $laraSec->getDependencies();
      if($composerLock === false) {
        $this->error('Unable to open composer.lock');
        return 1;
      }

      $packages = $composerLock['packages'];

      foreach($packages as $package) {
        list($vendor, $project) = explode('/', $package['name']);

        // Check for updates on Packagist. We are looking for updates with
        // same major and minor version, but higher patch version number.
        $updates = $Packagist->hasPatchUpdates($vendor, $project, $package['version']);
        uslep(20000); // We want to be nice with the packagist API
        if($updates === true) {
          // The package has a version with higher patch number.
          $this->comment($package['name']);
          $this->line('This package has a newer, compatible package available. You should consider updating.');
          $this->line('');
        } elseif($updates === false) {
          // The package had an unusual version number. Probably a development
          // version.
          $this->comment($package['name']);
          $this->line('You are using version '.$package['version'].'. This version number looks like a development version.');
          $this->line('');
        }

        // Check for known security vulnerabilities.
        $alerts = $laraSec->getSecurityAlerts($vendor, $project, $package['version']);
        if(sizeof($alerts) > 0) {
          $this->error($package['name']);
          foreach($alerts as $alert) {
            $this->info($package['version'].': '.$alert['title']);
            $this->line($alert['link']);
            $this->line('');
          }
        }
      }

      $this->comment('Scanned '.sizeof($packages).' packages, found '.sizeof($alerts).' vulnerabilities.');
    }
}
