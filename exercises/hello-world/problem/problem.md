Write a program that prints the text "Hello World" to the console (stdout).

----------------------------------------------------------------------
## HINTS
{{ cli }}
To make a PHP program, create a new file with a `.php` extension and start writing PHP! Execute your program by running it with the
`php` command. e.g.:

```sh
$ php program.php
```
{{ cli }}

{{ cloud }}
Your first solution file is waiting for you, notice the `.php` extension in it's name. We'll execute your solution file for you when you hit `Run` or `Verify`. 
{{ cloud }}

You can write to the console from a PHP program with the following code:

```php
<?php
echo "text";
```

The first line tells the PHP to interpret the code following it. It is required before any PHP code is written. Read more here: [http://php.net/manual/en/language.basic-syntax.phptags.php]()
The second line is the instruction to print out some text.

{{ cli }}
Place the code in to a text file using your favourite text editor, some popular editors are listed below:

* Sublime Text: [https://www.sublimetext.com/]()
* Atom: [https://atom.io/]()

Switch back to the terminal and run your code again with the same command as above:

```sh
$ php program.php
```
{{ cli }}

{{ cloud }}
Try hitting the *Run* button to execute your program.
{{ cloud }}

[//]: # (TODO: Contextual words ??? e.g. console <-> browser)
You should see the word "text" printed out on the console.

Now you must adapt the code to pass the challenge presented. Remember the challenge was: Write a program that prints the text "Hello World" to the console (stdout).

{{ cli }}
We have created you a file named `hello-world.php` in your current working directory, feel free to use it!
{{ cli }}

[//]: # (TODO: Verify...)
{{ verify ... ... ... }}
When you have finished and saved the file you must run the following (substituting program.php to the name of the file you created which contains your code)

```sh
$ {appname} verify program.php
```

When you have finished editing your program {cli}and saved the file{cli} you must verify your program. 

{{ verify program.php }}

{{ cli }}
Don't forget to substitute program.php for the name of the file you created which contains your code.
{{ cli }}

to proceed. Your program will be tested, a report will be generated, and the lesson will be marked 'completed' if you are successful.

----------------------------------------------------------------------
