<?php

namespace BrunoFontes\Memsource;

class Helper 
{

    public static function saveIntoFile(string $filename, string $filecontent): void
    {
        try {
            $f = fopen($filename, 'w+');
            fwrite($f, $filecontent);
            fclose($f);
        } catch (\Exception $e) {
            throw new \Exception("File could not be saved: {$e->error}", 1);
        }
    }

}
