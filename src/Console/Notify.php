<?php
namespace xqus\laraSec\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use xqus\laraSec\Notifications\ScanReportNotification;
use xqus\laraSec\laraSec;
use xqus\laraSec\VulnDb;


class Notify extends Command {
    protected $signature = 'larasec:notify';
    protected $description = '';

    public function handle() {

      Notification::route('mail', config('larasec.notify'))
            ->notify(new ScanReportNotification());
    }
}
