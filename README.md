# WHOIS

*[IN DEVELOPMENT]* This package provides functionality to fetch WHOIS information of a domain.

## Installation

```bash
composer require oarkhipov/whois
```

### Laravel

There is a service provider for Laravel.  
Add this to your providers array in `config/app.php`:

```php
Oarkhipov\Whois\Laravel\WhoisServiceProvider::class,
```

## Usage

Firstly, create `Fetcher` class instance:
```php
use Oarkhipov\Whois\Fetcher;

$fetcher = new Fetcher();
```

With Laravel you are able to typehint it like that:

```php
use Oarkhipov\Whois\Fetcher;

class TestController extends Controller
{
    private $whoisFetcher;

    public function __construct(Fetcher $fetcher)
    {
      $this->whoisFetcher = $fetcher;
    }
}
``` 

or resolve from container by `whois` name like that:

```php
$fetcher = $this->app->make('whois');
```

Now to retrieve WHOIS record:

```php
$whois = $fetcher->fetch('http://facebook.com');
```

Check wiki for a [list of supported WHOIS record fields](https://github.com/Oleg-Arkhipov/whois/wiki/Supported-WHOIS-record-fields)
and [tested top-level domains](https://github.com/Oleg-Arkhipov/whois/wiki/Tested-top-level-domains). 