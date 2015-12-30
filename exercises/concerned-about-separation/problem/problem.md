This problem is the same as the previous but introduces the concept of **classes**. You will need to create two files to solve this.

Create a program that prints a list of files in a given directory, filtered by the extension of the files. The first argument is the directory name and the second argument is the extension filter. Print the list of files (one file per line) to the console.

You must write a *class* file to do most of the work. The file must *define* a single class with a single function that takes **two** arguments: the directory name and the filename extension string in that order. The filename extension argument must be the same as what was passed to your program. Don't turn it into a regular expression or prefix with "." or do anything except pass it to your class method where you can do what you need to make your filter work.

You **must** not print directly to the console from your class, only from your original program.

The benefit of having a contract like this is that your class can be used by anyone who expects this contract. So your class could be used by anyone else who does learnyouphp, or the verifier, and just work.

----------------------------------------------------------------------
## HINTS

Create a new class by creating a new file that just contains your directory reading and filtering code in a class method. To define a *single method* *class*, use the following syntax:

```php
<?php

class DirectoryFilter
{
    public function filter($args)
    {
        /** ... */
    }
}
```

To use your new class in your original program file, use the `require_once` construct with the filename. So, if your file is named mymodule.php then:

```php
<?php
require_once __DIR__ . '/mymodule.php';
```

You can now create an instance of your class and assign it to a variable!

```php
<?php
$myFilter = new DirectoryFilter;
```

You can then call the method you defined with its required arguments.

Documentation on class basics can be found here:

[http://php.net/manual/en/language.oop5.basic.php]()

Documentation on `require_once` can be found here:

[http://php.net/manual/en/function.require-once.php]()

----------------------------------------------------------------------