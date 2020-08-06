<?php
namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use xqus\laraSec\Notifications\ScanReportNotification;
use xqus\laraSec\laraSec;


class Notify extends Command {
    protected $signature = 'larasec:notify';
    protected $description = '';

    public function handle() {
      if(!config('larasec.notify') || config('larasec.notify') === '' || config('larasec.notify') === 'email@example.com') {
        $this->error('No email address configured.');
        return 1;
      }

      $laraSec = new laraSec;

      $alerts  = $laraSec->getAlerts();
      $updates = $laraSec->getUpdates();

      if(sizeof($alerts) > 0 || sizeof($updates) > 0) {
        Notification::route('mail', config('larasec.notify'))
          ->notify(new ScanReportNotification($alerts, $updates));
      }

      return 0;
    }
}
