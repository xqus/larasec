<?php

namespace xqus\laraSec;

use Symfony\Component\Yaml\Yaml;
use Composer\Semver\Semver;
use Illuminate\Support\Facades\Storage;

class laraSec {

  public function getDependencies() {
    $path = base_path();

    return json_decode(
      file_get_contents($path . "/composer.lock"),
      true,
    );
  }

  public function updateAlertsDb() {
    $url = 'https://github.com/FriendsOfPHP/security-advisories/archive/master.zip';
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $url);

    Storage::disk('local')->put('larasec/master.zip',  $response->getBody());

    $zip = new \ZipArchive;
    $res = $zip->open(storage_path('app/larasec').'/master.zip');
    if ($res === TRUE) {
      $zip->extractTo(storage_path('app/larasec'));
      $zip->close();
    }
    Storage::disk('local')->delete('larasec/master.zip');
  }

  public function getSecurityAlerts($vendor, $name, $version) {
    $alerts = array();

    $path = storage_path('app/larasec') . '/security-advisories-master/';
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
