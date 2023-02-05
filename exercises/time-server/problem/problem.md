Write a **TCP time server**!

Your server should listen to TCP connections on the IP address provided as the first argument and the port provided by the second argument to your program. For each connection you must write the current date & 24 hour time in the format:

```
"YYYY-MM-DD hh:mm:ss"
```

followed by a **newline** character. Month, day, hour, minute and second must be *zero-filled* to 2 integers. For example:

```
"2013-07-06 17:42:30"
```

----------------------------------------------------------------------
## HINTS

For this exercise we'll be creating a raw TCP server. We will be using the core PHP `socket_*` functions. These functions are a thin wrapper around the C libraries.

To create a server you need to use the functions `socket_create`, `socket_bind` & `socket_listen`. Once the socket is 
listening, you can accept connections from it, which will return a new socket connected to the client whenever a client
connects.

`socket_create` returns a server resource. You must bind it to a host and port and then start listening.

A typical PHP TCP server looks like this:

```php
<?php
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($server, '127.0.0.1', 8000);

socket_listen($sock);

$client = socket_accept($server);
```

Remember to use the IP address & port number supplied to you as the first and second command-line argument.

You can read and write to the socket by using `socket_read` and `socket_write`.  For this exercise we only need to write data and then close the socket.

Use `socket_write($client, $data, strlen($data))` to write data to the socket and then `socket_close($socket)` to close the socket.

{{ doc 'PHP streams' en sockets.examples.php function.stream-socket-server.php }}

To create the date you'll need to create a custom format from the PHP `DateTime` object. The various parameters to `format()` will 
help you. 

{{ doc DateTime en class.datetime.php }}

----------------------------------------------------------------------
