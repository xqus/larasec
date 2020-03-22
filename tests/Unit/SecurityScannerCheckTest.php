<?php

namespace xqus\laraSec\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use xqus\laraSec\Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use xqus\laraSec\laraSec;

class SecurityScannerCheckTest extends TestCase
{
    /** @test */
    function lookup_works_1()
    {
      Artisan::call('larasec:update');
      $laraSec = new laraSec;
      $alerts = $laraSec->getSecurityAlerts('laravel', 'framework', '7.0.0');
      $this->assertTrue(sizeof($alerts) == 1);
    }

    /** @test */
    function lookup_works_2()
    {
      Artisan::call('larasec:update');
      $laraSec = new laraSec;
      $alerts = $laraSec->getSecurityAlerts('laravel', 'framework', '7.1.2');
      $this->assertTrue(sizeof($alerts) == 0);
    }

    /** @test */
    function lookup_works_3()
    {
      Artisan::call('larasec:update');
      $laraSec = new laraSec;
      $alerts = $laraSec->getSecurityAlerts('laravel', 'framework', '4.1.2');
      echo sizeof($alerts);
      $this->assertTrue(sizeof($alerts) == 4);
    }
}
