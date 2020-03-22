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

  public function getStoragePath() {
    return storage_path().'/app/larasec/';
  }

  public function getTmpStoragePath() {

  }

  public function prepeareStorage() {
    Storage::makeDirectory('larasec');
  }

  public function updateAlertsDb() {
    $this->prepeareStorage();

    $url = 'https://github.com/FriendsOfPHP/security-advisories/archive/master.zip';
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', $url);

    file_put_contents($this->getStoragePath().'/master.zip', $response->getBody());

    $zip = new \ZipArchive;
    $res = $zip->open($this->getStoragePath().'/master.zip');
    if ($res === TRUE) {
      $zip->extractTo($this->getStoragePath());
      $zip->close();
    }
    unlink($this->getStoragePath().'/master.zip');
  }

  public function getSecurityAlerts($vendor, $name, $version) {
    $alerts = array();

    $path = $this->getStoragePath() . '/security-advisories-master/';
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
