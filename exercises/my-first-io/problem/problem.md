Write a program that uses a single filesystem operation to read a file and print the number of newlines (\n) it contains to the console (stdout), similar to running `cat file | wc -l`.

The full path to the file to read will be provided as the first command-line argument. You do not need to make your own test file. 

----------------------------------------------------------------------
## HINTS

To perform a filesystem operation you can use the global PHP functions.

To read a file, you'll need to use `file_get_contents('/path/to/file')`. This method will *return* a `string` containing the complete contents of the file.

Documentation on the `file_get_contents` function can be found by pointing your browser here:
  [http://php.net/manual/en/function.file-get-contents.php]()

If you're looking for an easy way to count the number of newlines in a string, recall the PHP function `explode` can be used to turn a string into an array of substrings and that "\n" can be used as a delimiter.

----------------------------------------------------------------------