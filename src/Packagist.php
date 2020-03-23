<?php
namespace xqus\laraSec;

use Composer\Semver\Semver;

class Packagist {

  public function hasPatchUpdates($vendor, $package, $version) {
    $verArr = explode('.', $version);

    if(sizeof($verArr) === 3) {
      list($major, $minor, $patch) = $verArr;
      $minVer = $version;
      $maxVer = $major.'.'.$minor.'.999999';
      //$maxVer = '999.99999.999999';

      $client = new \GuzzleHttp\Client();
      $response = $client->request('GET', 'https://packagist.org/packages/'.$vendor.'/'.$package.'.json');
      if($response->getStatusCode() !== 200) {
        return null;
      }
      $json = json_decode($response->getBody());

      foreach ($json->package->versions as $ver => $versionData) {
        // Ignore development versions. We don't want to suggest installing
        // them anyways.
        if(substr($ver, -4) == '-dev') {
          continue;
        }
        if(Semver::satisfies($ver, '>'.$minVer.' <'.$maxVer)) {
          return true;
        }
      }
      return null;
    } else {
      return false;
    }


  //
  }


}
