Write an HTTP **server** that serves JSON data when it receives a POST request to `/reverse`, `/swapcase` and `/titleize`. 

The POST data will contain a single parameter `data` which you will need to manipulate depending on the endpoint.

## /reverse  

A request with `data = "PHP School is awesome!"` should return the response:

```json
{
  "result": "!emosewa si loohcS PHP"
}
```

## /swapcase  

A request with `data = "No, It Really Is..."` should return the response:

```json
{
  "result": "nO, iT rEALLY iS..."
}
```

## /titleize  

A request with `data = "you know you love it, don't you?"` should return the response:

```json
{
  "result": "You Know You Love It, Don't You?"
}
```

You should use the routing library `klein/klein` for this task pulling it in as a dependency through Composer. 

You will also be required to use `danielstjules/stringy` to manipulate the data as this correctly handles multibyte characters. 

----------------------------------------------------------------------
## HINTS

Point your browser to [https://getcomposer.org/doc/00-intro.md](https://getcomposer.org/doc/00-intro.md) which will walk you through **Installing Composer** if you havn't already!

Use `composer init` to create your `composer.json` file with interactive search. 

For more details look at the docs for...

**Composer** - [https://getcomposer.org/doc/01-basic-usage.md](https://getcomposer.org/doc/01-basic-usage.md) 
**Klein** - [https://github.com/chriso/klein.php](https://github.com/chriso/klein.php)
**Stringy** - [https://github.com/danielstjules/Stringy](https://github.com/danielstjules/Stringy)
  
Oh, and don't forget to use the Composer autoloader with:

```php
require_once __DIR__ . '/vendor/autoload.php';
```
----------------------------------------------------------------------