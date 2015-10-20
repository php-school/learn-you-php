Create a program that prints a list of files in a given directory, filtered by the extension of the files. You will be provided a directory name as the first argument to your program (e.g. '/path/to/dir/') and a file extension to filter by as the second argument.

For example, if you get 'txt' as the second argument then you will need to filter the list to only files that **end with .txt**. Note that the second argument _will not_ come prefixed with a '.'.

The list of files should be printed to the console, one file per line.

----------------------------------------------------------------------
## HINTS

The `DirectoryIterator` class takes a pathname as its first argument. Using an iterator in a `foreach` loop will provide you with a `SplFileInfo` object for each file.

```php
<?php
foreach (new DirectoryIterator('/some/path') as $file) {
    
}
```

Documentation on the `SplFileInfo` class can be found by pointing your browser here:

[http://php.net/manual/en/class.splfileinfo.php]()

You may also find `SplFileInfo`'s `getExtension()` method helpful

Documentation on the `getExtension()` method can be found by pointing your browser here:
[http://php.net/manual/en/splfileinfo.getextension.php]()

----------------------------------------------------------------------