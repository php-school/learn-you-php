Write a program that takes an array of filepaths as arguments, filtering out files that do not exist and mapping existing files to `SplFileObject`'s.

Finally output the basename of the files, each on a new line.

The full path of the files to read will be provided as the command line arguments. You do not need to make your own test files. 

----------------------------------------------------------------------
## HINTS

Remember the first argument will be the programs file path and not an argument passed to the program.

You will be expected to make use of core array functions, `array_shift`, `array_filter` and `array_map`. 

To check a file exists you will need to use `file_exists($filePath)`. This method will *return* a *boolean* `true` or `false`.

Documentation on the `SplFileObject` class can be found by pointing your browser here:
  [http://php.net/manual/en/class.splfileobject.php]()

----------------------------------------------------------------------
