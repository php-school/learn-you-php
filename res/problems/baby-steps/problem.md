Write a program that accepts one or more numbers as command-line arguments and prints the sum of those numbers to the console (stdout).

----------------------------------------------------------------------
## HINTS

You can access command-line arguments via the global `$argv` array.

To get started, write a program that simply contains:

```php
var_dump($argv);
```

Run it with `php program.php` and some numbers as arguments. e.g:

```sh
$ php program.php 1 2 3
```

In which case the output would be an array looking something like:

```php
array(4) {
  [0] =>
  string(7) "program.php"
  [1] =>
  string(1) "1"
  [2] =>
  string(1) "2"
  [3] =>
  string(1) "3"
}
```

You'll need to think about how to loop through the number arguments so  you can output just their sum. The first element of the `$argv` array is always the name of your script. eg program.php, so you need to start at the 2nd element (index 1), adding each item to the total until you reach the end of the array.

Also be aware that all elements of `$argv` are strings and you may need to *coerce* them into numbers. You can do this by prefixing the property with a cast `(int)` or just adding them. PHP will coerce it for you.

`{appname}` will be supplying arguments to your program when you run `{appname} verify program.php` so you don't need to supply them yourself. To test your program without verifying it, you can invoke it with `{appname} run php.php`. When you use `run`, you are invoking the test environment that `{appname}` sets up for each exercise.

----------------------------------------------------------------------
