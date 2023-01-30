Write an HTTP **server** that serves JSON data when it receives a POST request to `/reverse`, `/snake` and `/titleize`. 

The POST data will contain a single parameter `data` which you will need to manipulate depending on the endpoint.

### /reverse  

A request with `data = "PHP School is awesome!"` should return the response:

```json
{
  "result": "!emosewa si loohcS PHP"
}
```

### /snake  

A request with `data = "No, It Really Is..."` should return the response:

```json
{
  "result": "no_it_really_is"
}
```

### /titleize  

A request with `data = "you know you love it, don't you?"` should return the response:

```json
{
  "result": "You Know You Love It, Don't You?"
}
```

You should use the routing library `league/route` for this task, pulling it in as a dependency through Composer. `league/route` allows us to define actions which respond to requests based on the request URI and HTTP method.

The library works by accepting a PSR7 request and returns to you a PSR7 response. It is up to you to marshal the request object and output the response to the browser.

There are a few other components we need, in order to use `league/route`:

 * **laminas/laminas-diactoros** - For the PSR7 requests and responses.
 * **laminas/laminas-httphandlerrunner** - For outputting the PSR7 response to the browser.

`laminas/laminas-diactoros` is a PSR7 implementation. PSR's are standards defined by the PHP-FIG, a committee of PHP projects, attempting to increase interoperability in the PHP ecosystem. PSR7 is a standard for modelling HTTP requests. We can use the `laminas/laminas-diactoros` package to marshal a PSR7 request object from the PHP super globals like so:

```php
$request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();
```

`$request` is now a PSR7 request, that can be used with `league/route`.

`laminas/laminas-httphandlerrunner` provides a simple method to output the PSR7 response to the browser, handling headers, status codes and the content. Use it like so:

```php
(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
```

Where `$response` is a PSR7 response.

In between this, you will need to define your routes and execute the dispatching mechanism to receive a response. Refer to the `league\route` [documentation](https://route.thephpleague.com/5.x/usage/).

Each route action will be passed the PSR7 request where you can access the request parameters and body. To access the `data` key from the request body, you would use the following:

```php
$data = $request->getParsedBody()['data'] ?? '';
```

In each action, you are expected to return a PSR7 response. `laminas/laminas-diactoros` provides a few ways to accomplish this:

A text response:

```php
$response = new \Laminas\Diactoros\Response('My Content');
```

A JSON response:

```php
$response = (new \Laminas\Diactoros\Response(json_encode(['desert' => 'cookies'])))
    ->withAddedHeader('Content-Type', 'application/json; charset=utf-8'');
```

Or you could use the helper class, which takes care of encoding and setting the correct headers:

```php
$response = (new \Laminas\Diactoros\Response\JsonResponse(['desert' => 'cookies']));
```

Finally, you will also be required to use `symfony/string` to manipulate the data as this correctly handles multibyte characters. 

----------------------------------------------------------------------
## HINTS

{{ cli }}
Point your browser to [https://getcomposer.org/doc/00-intro.md](https://getcomposer.org/doc/00-intro.md) which will walk you through **Installing Composer** if you haven't already!

Use `composer init` to create your `composer.json` file with interactive search.
{{ cli }}

{{ cloud }}
Composer is installed and ready to go on cloud, use the Composer Deps button in the editor to search for and install your dependencies. While you should read the documentation for [Composer](https://getcomposer.org/doc/00-intro.md), it's important to note that the way we manage dependencies on PHP School cloud, is not how you would manage them in your own projects. We abstract away the `composer.json` file to keep it simple. 
{{ cloud }}

For more details look at the docs for...

* **Composer** - [https://getcomposer.org/doc/01-basic-usage.md](https://getcomposer.org/doc/01-basic-usage.md) 
* **League Route** - [https://route.thephpleague.com/5.x/usage/](https://route.thephpleague.com/5.x/usage/)
* **Symfony String** - [https://symfony.com/doc/current/components/string.html](https://symfony.com/doc/current/components/string.html)
* **PSR** - [https://www.php-fig.org/psr/](https://www.php-fig.org/psr/)
* **PSR 7** - [https://www.php-fig.org/psr/psr-7/](https://www.php-fig.org/psr/psr-7/)
  
Oh, and don't forget to use the Composer autoloader with:

```php
require_once __DIR__ . '/vendor/autoload.php';
```
----------------------------------------------------------------------
