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
        return array_map(
            function (DirectoryIterator $file) {
                return $file->getFilename();
            },
            array_filter(
                iterator_to_array(new DirectoryIterator($directory)),
                function (DirectoryIterator $file) use ($ext) {
                    return $file->getExtension() === $ext;    
                }
            )
        );
    }
}
