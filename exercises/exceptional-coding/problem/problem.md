Write a program that takes an array of filepaths as arguments and outputs the basename of each, separated by a new line. 

Every file should exist but under exceptional circumstances some files may not. If this occurs, output a message similar to the below.  

```
Unable to open file at path '/file/path'
```

The full path of the files to read will be provided as the command line arguments. You do not need to make your own test files. 

----------------------------------------------------------------------
## HINTS

You are urged to use `try... catch` logic here along with the SplFileObject contruct which throws a `RuntimeException` when a file does not exist.

Documentation on the `SplFileObject` class can be found by pointing your browser here:
  [http://php.net/manual/en/class.splfileobject.php]()

----------------------------------------------------------------------
