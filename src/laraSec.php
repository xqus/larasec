<?php
namespace xqus\laraSec;

use Symfony\Component\Yaml\Yaml;
use Composer\Semver\Semver;
use Illuminate\Support\Facades\Storage;

class laraSec {

  public function getDependencies() {
    $path = base_path();
    if(!file_exists($path . "/composer.lock")) {
      return false;
    }

    return json_decode(
      file_get_contents($path . "/composer.lock"),
      true,
    );
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
}
