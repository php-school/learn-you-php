Write an HTTP **server** that serves JSON data when it receives a GET request to the path '/api/parsetime'. Expect the request to contain a query string with a key 'iso' and an ISO-format time as the value.

For example:

  /api/parsetime?iso=2015-11-15T20:18:04+0000

The JSON response should contain only 'hour', 'minute' and 'second' properties. For example:

```json
{
  "hour": 14,
  "minute": 23,
  "second": 15
}
```

Add a second endpoint for the path '/api/unixtime' which accepts the same query string but returns UNIX epoch time in milliseconds (the number of milliseconds since 1 Jan 1970 00:00:00 UTC) under the property 'unixtime'.

For example:

```json
{ "unixtime": 1376136615474 }
```

----------------------------------------------------------------------
## HINTS

The `$_SERVER` super global array has a `REQUEST_URI` property that you will need to use to *"route"* your requests for the two endpoints.

You can parse the URL using the global `parse_url` function. The result will be an array of helpful properties.
You can access the query string properties via the `$_GET` super global array.

Documentation on the `parse_url` function can be found by pointing your browser here:
    [http://php.net/manual/en/function.parse-url.php]()
  
Your response should be in a JSON string format. Look at `json_encode` for more information.

You should also be a good web citizen and set the Content-Type properly:

```php
header('Content-Type: application/json');
```

The PHP `DateTime` object can print dates in ISO format, e.g. `(new \DateTime())->format('u');`. It can also parse this format if you pass the string into the `\DateTime` constructor. The various parameters to `format()` will also
come in handy. You can find the documentation here:
    [http://php.net/manual/en/class.datetime.php]()

----------------------------------------------------------------------