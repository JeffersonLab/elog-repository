# ElogRepository

A Laravel package for easing interaction with the Jlab logbooks data API.

## Installation

Add the repository to the repositories section of your project's composer.json file:
```php
  "repositories": {
    "jlab/elog-repository": {
      "type": "vcs",
      "url": "https://github.com/JeffersonLab/elog-repository.git"
    }
}
```
And the usual composer commands:

``` bash
$ composer require jlab/elog-repository
```

## Usage
Check if a lognumber exists
```php
use Jlab\ElogRepository\ElogRepository;
$repo = new ElogRepository('https://logbooks.jlab.org/api/elog');
if ($repo->exists(3875817)){
  // do something
}
```

Retrieve the details of a logentry
```php
use Jlab\ElogRepository\ElogRepository;
$repo = new ElogRepository('https://logbooks.jlab.org/api/elog');
$entry = $repo->find(3875817)
  var_dump($entry);
}
```

Retrieve a collection of summary data for logentries matching specified conditions. 
```php
use Jlab\ElogRepository\ElogRepository;
$repo = new ElogRepository('https://logbooks.jlab.org/api/elog');
    $entries = $this->repo->where('title','restored')
        ->where('startdate','2021-05-06')
        ->where('enddate', '2021-05-07')
        ->where('book','SLOG')
        ->get();
    var_dump($entries->pluck('title')); 
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing
The test suite can be executed by installing the package's dependencies via composer and then executing phpunit.  The tests require non-password protected access to https://logbooks.jlab.org/ 

``` bash
$ composer update
$ vendor/bin/phpunit 
```
