Write a program that takes an array of filepaths as arguments and outputs the message below for each file that does `NOT` exist along with a line break after each. 

```
Unable to open path file at path '/file/path'
```

The full path of the files to read will be provided as the command line arguments. You do not need to make your own test files. 

----------------------------------------------------------------------
## HINTS

You are urged to use `try... catch` logic as a method of control flow here.

You should make use of the SplFileObject contruct which throws a `RuntimeException` when a files does not exist.

You will be banned from using the `array_filter` and `file_exists` functions. 

Documentation on the `SplFileObject` class can be found by pointing your browser here:
  [http://php.net/manual/en/class.splfileobject.php]()

----------------------------------------------------------------------

## DISCLAIMER

Here we are encouraging the use of exceptions as a method of control flow. There are often much better and more elagent solutions so ensure you use this logic spareingly in production code as it can be frowned upon. 