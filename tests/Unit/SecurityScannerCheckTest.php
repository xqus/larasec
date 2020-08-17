<?php

namespace xqus\laraSec\Tests\Unit;

use xqus\laraSec\laraSec;
use xqus\laraSec\Tests\TestCase;

class SecurityScannerCheckTest extends TestCase
{
    /** @test */
    public function lookup_works_1()
    {
        $laraSec = new laraSec;
        $alerts = $laraSec->getSecurityAlerts('laravel', 'framework', '7.0.0');
        $this->assertTrue(sizeof($alerts) == 1);
    }

    /** @test */
    public function lookup_works_2()
    {
        $laraSec = new laraSec;
        $alerts = $laraSec->getSecurityAlerts('laravel', 'framework', '7.1.2');
        $this->assertTrue(sizeof($alerts) == 0);
    }

    /** @test */
    public function lookup_works_3()
    {
        $laraSec = new laraSec;
        $alerts = $laraSec->getSecurityAlerts('laravel', 'framework', '4.1.2');
        $this->assertTrue(sizeof($alerts) == 4);
    }
}
