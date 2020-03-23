<?php

namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use xqus\laraSec\laraSec;
use xqus\laraSec\VulnDb;

class Update extends Command {
    protected $signature = 'larasec:update';
    protected $description = 'Update the vulnerability database.';

    public function handle() {
      $VulnDb = new VulnDb;
      $this->comment('Updating vulnerability database..');
      $VulnDb->update();      
    }
}
