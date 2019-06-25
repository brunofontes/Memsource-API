# PHP Memsource API

I am creating this Memsource API as a way to learn how to deal with one and to use with my next projects. I will not create the fully functional API here, but feel free to send a pull request if it were interesting for you.

There are other Memsource API repositories on GibHub that appears to be fully functional if you need it.

## Installing

Install it with [Composer](https://getcomposer.org/):

1. Create a `composer.json` file with the following content:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/brunofontes/Memsource-API"
        }
    ],
    "require": {
        "brunofontes/memsource-api": "*"
    }
}
```

2. Run `php composer.phar install`
3. Add the following line on your .php file:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

## Using

This repository returns a JSON string for almost any command.
If you are not sure how to use it, just convert it to an object or an array as follows:

```php
$myObject = json_decode($response);
$myArray = json_decode($response, true);
```

### Create an instance

```php
$memsource = new \BrunoFontes\Memsource();
```

- If you have already an access token, just include it:

```php
$memsource = new \BrunoFontes\Memsource($token);
```

### Getting an Access Token

To be able to use the Memsource API, you need an **access token**. In order to get it, just follow the instructions below.

#### Register as a developer on Memsource website

So you will receive your:

- *client id*
- *client secret*

#### Get an Authorization Code

```php
$memsourceUrl = $memsource->oauth()->getAuthorizationCodeUrl($cliend_id, $callback_uri);
```

Redirect your browser to this returned `$url` so the user can login via *oauth*.

The `$callback_uri` will be called by *Memsource* with a `$_GET['code']` that contains your Authorization Code, which you can use to...

#### Get an Access Token

```php
$authCode = $_GET['code'];
$token = $memsource->oauth()->getAccessToken($authCode, $client_id, $client_secret, $callback_uri);
```

Safely store this `$token` with the related user data and use it to instantiate the class whenever it were necessary.

### Project

#### Project list

To list all projects:

```php
$projectList = $memsource->project()->listProjects;
```

To use filters, add the API filter as parameter:

```php
$projectList = $memsource->project()->list(['name' => 'Project X']);
```

#### Get Project

```php
$projectList = $memsource->project()->get($projectUid);
```

### Jobs

#### List Jobs

Only projectUid is essencial:

```php
$jobs = $memsource->jobs()->list($projectUid, ['count' => true, 'filename' => 'my_file.html']);
```

### Bilingual Files

#### Download Bilingual File

```php
$memsource->BilingualFile()->download($projectUid, ['JobUid1', 'jobUid2'], 'download.mxliff');
```

#### Upload Bilingual File

```php
$parameters = ['format' => 'MXLF', 'saveToTransMemory' => 'None', 'setCompleted' => 'false'];
$result = $api->bilingualFile()->upload('upload.mxliff', $parameters);
```
