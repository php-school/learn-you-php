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

We've created you an empty file, look in the file tree for `solution.php`. That's your starting point. 

We'll execute your solution file for you when you press the `Run` or `Verify` buttons. The `Run` button simply runs your program and captures the output, displaying it for you to view. The `Verify` button runs your program but performs some extra tasks, such as comparing the output, checking for certain structures and function calls, etc. It then displays the result of those verifications.

Both `Run` and `Verify` execute your program with random inputs which are determined by the current exercise. For example one exercise might generate a bunch of numbers to pass to your program, where another one might pass you a JSON encoded string. 

{{ cloud }}

You can write to the console from a PHP program with the following code:

```php
<?php
echo "text";
```

The first line tells PHP to interpret the code following it. It is required before any PHP code is written. The second line is the instruction to print out some text.

{{ doc 'PHP tags' en language.basic-syntax.phptags.php }}

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
Try pressing the `Run` button in the bottom right corner to execute your program.
{{ cloud }}

You should see the word "text" printed out on the console.

Now you must adapt the code to pass the presented challenge. Remember, the challenge was: Write a program that prints the text "Hello World" to the console.

{{ cli }}
We have created you a file named `hello-world.php` in your current working directory, feel free to use it!
{{ cli }}

{{ cli }}
When you have finished editing your program and saved the file you must run the following (substituting program.php to the name of the file you created which contains your code)

```sh
$ {appname} verify program.php
```
{{ cli }}

{{ cloud }}
When you have finished editing your program you must verify it. Click the `Verify` button in the bottom right corner.
{{ cloud }}

Your program will be tested, a report will be generated, and the lesson will be marked 'completed' if you are successful.

----------------------------------------------------------------------
