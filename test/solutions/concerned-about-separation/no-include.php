<?php

class DirectoryFilter
{
    /**
     * @param string $directory
     * @param string $ext
     * @return array
     */
    public function getFiles($directory, $ext)
    {
        return array_filter(
            scandir($directory),
            function ($file) use ($ext) {
                return pathinfo($file, PATHINFO_EXTENSION) === $ext;
            }
        );
    }
}

array_map(function ($fileName) {
    echo $fileName . "\n";
}, (new DirectoryFilter)->getFiles($argv[1], $argv[2]));