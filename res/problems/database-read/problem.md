Write a program that receives a database connection string (DSN). Connect to the database, query it and update some data.

Display the information of all the users in the database table `users` who's is are over 30. Print out each row as a new line formatted like:

`User: Jim Morrison Age: 27 Sex: Male` followed by a line break.

Finally you will be given a random name as the second argument to your program, you should update the row in the `users` table which corresponds to this name. You should change the name to `David Attenborough`.

----------------------------------------------------------------------
## HINTS

This is an exercise introducing databases and PDO. PDO is a powerful abstraction library for dealing with different database vendors in a consistent manner. You can read the PDO manual here:

  [http://php.net/manual/en/book.pdo.php]()
  
A short introduction can be found here:
  
  [http://www.phptherightway.com/#pdo_extension]()
  
The most interesting class will be `\PDO`. The first parameter is the DSN string. The second and third are the username and password for the database. They are not needed for this exercise and can be left out.
  
The `users` table is structured as follows

```
+----+-----------------+-----+--------+
| id |      name       | age | gender |
+----+-----------------+-----+--------+
|  1 | Mark Corrigan   |  32 | male   |
|  2 | Jeremy Usbourne |  30 | male   |
+----+-----------------+-----+--------+
```

The table will be pre-populated with random data.

In order to get the data you will most likely want the `query` method. Which you can pass an SQL statement to. `query` returns an instance of `PDOStatement` which you can iterate over in a foreach loop, like so:

```php
<?php
foreach ($pdo->query('SELECT * FROM users') as $row) {
}
```

`$row` is now an array of data. The key will be the columns and the value is the database value


You should use prepared statements to perform the updating. You should be most interested in the `prepare` and `execute` methods.

Remember the first argument will be the programs file path and not an argument passed to the program.

----------------------------------------------------------------------
