# Pushover PHP

Pushover PHP is a very light, simple API wrapper for the Pushover API written in PHP.

## Installing

Pushover PHP is available through Packagist via Composer.

```json
"require": {
	"donatj/pushover": "*"
}
```

## Usage

```php
$po = new donatj\Pushover('myapikey', 'myuserkey');

$po->send('Hello World!');
$po->send('Goodbye Cruel World...', array('priority' => 1));
```

## Documentation

On *success* `Pushover->send` returns a **truth-y** array like:

```php
array(
    'status'  => 1
    'request' => 2f4e9c7140df52d7d8b16ffb8adf1c2a
)
```
	
On *failure* `Pushover->send` returns **false** which allows simple

```php
if( !$po->send('Hello World!') ) { 
	die('oh no!');
}
```
