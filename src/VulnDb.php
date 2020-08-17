<?php

namespace xqus\laraSec;

use Illuminate\Support\Facades\Storage;

class VulnDb
{
    protected $dir = null;

    protected $tmpDir = null;

    protected $source = 'https://github.com/FriendsOfPHP/security-advisories/archive/master.zip';

    public function __construct()
    {
        $this->tmpDir = sys_get_temp_dir().'/larasec-'.time();
    }

    public function update()
    {
        if (! is_dir($this->tmpDir)) {
            mkdir($this->tmpDir);
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->source);

        file_put_contents($this->tmpDir.'/master.zip', $response->getBody());

        $zip = new \ZipArchive;
        $res = $zip->open($this->tmpDir.'/master.zip');
        if ($res === true) {
            $zip->extractTo($this->tmpDir);
            $zip->close();
        }

        $this->copyToLaravel($this->tmpDir.'/security-advisories-master');
    }

    private function copyToLaravel($dir)
    {
        $basePath = $this->tmpDir.'/security-advisories-master/';
        if (is_dir($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != '.' && $entry != '..') {
                        if (is_dir($dir.'/'.$entry)) {
                            $this->copyToLaravel($dir.'/'.$entry);
                        } elseif (substr($entry, -5) == '.yaml') {
                            $fileName = str_replace($basePath, '', $dir).'/'.$entry;
                            Storage::put('larasec/'.$fileName, file_get_contents($basePath.$fileName));
                        }
                    }
                }
                closedir($handle);
            }
        }
    }
}
