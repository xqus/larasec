<?php
namespace xqus\laraSec;

use Symfony\Component\Yaml\Yaml;
use Composer\Semver\Semver;
use Illuminate\Support\Facades\Storage;
use xqus\laraSec\Packagist;

class laraSec {

  public function getDependencies() {
    $path = base_path();
    if(!file_exists($path . "/composer.lock")) {
      return false;
    }

    $composerLock = json_decode(
      file_get_contents($path . "/composer.lock"),
      true,
    );

     return $composerLock['packages'];
  }

  public function getSecurityAlerts($vendor, $name, $version) {
    $alerts = array();

    $directory = 'larasec/'.$vendor.'/'.$name;

    $files = Storage::files($directory);

    foreach($files as $file) {
      $contents = Storage::get($file);
      $yaml = Yaml::parse($contents);
      foreach($yaml['branches'] as $branch) {
        $isVulnerable = true;
        foreach($branch['versions'] as $constraints) {
          if(!Semver::satisfies($version, $constraints)) {
            $isVulnerable = false;
          }
        }
        if($isVulnerable) {
          $alerts[] = [
            'title' => $yaml['title'],
            'link'  => $yaml['link'],
          ];
        }
      }
    }
    return $alerts;
  }

  public function getAlerts() {
    $packages = $this->getDependencies();
    $pkgAlerts = array();

    foreach($packages as $package) {
      list($vendor, $project) = explode('/', $package['name']);

      // Check for known security vulnerabilities.
      $alerts = $this->getSecurityAlerts($vendor, $project, '7.0.0');
      if(sizeof($alerts) > 0) {
        foreach($alerts as $alert) {
          $pkgAlerts[] = [
            'package' => $package['name'],
            'version' => $package['version'],
            'title'   => $alert['title'],
            'link'    => $alert['link'],
          ];
        }
      }
    }
    return $pkgAlerts;
  }

  public function getUpdates() {
    $packages = $this->getDependencies();
    $pkgUpdates = array();

    $Packagist = new Packagist;

    foreach($packages as $package) {
      list($vendor, $project) = explode('/', $package['name']);

      // Check for updates on Packagist. We are looking for updates with
      // same major and minor version, but higher patch version number.
      $updates = $Packagist->hasPatchUpdates($vendor, $project, $package['version']);
      usleep(20000); // We want to be nice with the packagist API
      if($updates === true) {
        // The package has a version with higher patch number.
        $pkgUpdates[] = [
          'package'     => $package['name'],
          'version'     => $package['version'],
          'description' => 'This package has a newer, compatible package available. You should consider updating.',
        ];
      } elseif($updates === false) {
        // The package had an unusual version number. Probably a development
        // version.
        $pkgUpdates[] = [
          'package'     => $package['name'],
          'version'     => $package['version'],
          'description' => 'This version number looks like a development version.',
        ];
      }
    }
    return $pkgUpdates;
  }
}
