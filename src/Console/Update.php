<?php

namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use xqus\laraSec\laraSec;

class Update extends Command {
    protected $signature = 'larasec:update';
    protected $description = 'Update the vulnerability database.';

    public function handle() {
      $laraSec = new laraSec;
      
      $laraSec->updateAlertsDb();
    }
}
