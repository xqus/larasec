<?php

namespace xqus\laraSec\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use xqus\laraSec\Tests\TestCase;

class UpdateVulnDbTest extends TestCase
{
    /** @test */
    function the_update_command_downloads_files()
    {


        Artisan::call('larasec:update');

        $this->assertFalse(file_exists(storage_path().'/app/larasec/master.zip'));
        echo storage_path().'/app/larasec/security-advisories-master/laravel/framework/2020-03-13-1.yaml';
        $this->assertTrue(file_exists(storage_path().'/larasec/app/security-advisories-master/laravel/framework/2020-03-13-1.yaml'));
    }
}
