<?php

namespace xqus\laraSec\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use xqus\laraSec\Tests\TestCase;

class UpdateVulnDbTest extends TestCase
{
    /** @test */
    public function the_update_command_downloads_files()
    {
        $this->assertTrue(Storage::exists('larasec/laravel/framework/2020-03-13-1.yaml'));
    }
}
