<?php

namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use xqus\laraSec\VulnDb;

class Update extends Command
{
    protected $signature = 'larasec:update';
    protected $description = 'Update the vulnerability database.';

    public function handle()
    {
        $this->comment('Updating vulnerability database..');

        $VulnDb = new VulnDb;
        $VulnDb->update();
    }
}
