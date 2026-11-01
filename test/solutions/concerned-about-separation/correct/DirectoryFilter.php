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
