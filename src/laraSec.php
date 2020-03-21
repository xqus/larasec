<?php

namespace xqus\laraSec;

use Symfony\Component\Yaml\Yaml;
use Composer\Semver\Semver;

class laraSec {

  public function getDependencies() {
    $path = base_path();

    return json_decode(
      file_get_contents($path . "/composer.lock"),
      true,
    );
  }

  public function getSecurityAlerts($vendor, $name, $version) {
    $alerts = array();

    $path = base_path() . '/vendor/sensiolabs/security-advisories/';
    $projectPath = $path.$vendor.'/'.$name;
    if(file_exists($projectPath)) {
      $d = dir($projectPath);
      while (false !== ($entry = $d->read())) {
        if(substr($entry, -5) === '.yaml') {
          $yaml = Yaml::parseFile($projectPath.'/'.$entry);
          foreach($yaml['branches'] as $branch) {
            foreach($branch['versions'] as $constraints) {
              $isVulnerable = true;
              if(!Semver::satisfies($version, $constraints)) {
                $isVulnerable = false;
              }
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
      $d->close();
    }

    return $alerts;
  }
}
