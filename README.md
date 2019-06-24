# PHP Memsource API

I am creating this Memsource API as a way to learn how to deal with one and to use with my next projects. I will not create the fully functional API here, but feel free to send a pull request if it were interesting for you.

There are other Memsource API repositories on GibHub that appears to be fully functional if you need it.

## Getting an Access Token

To be able to use the Memsource API, you need an **access token**, but to get it, you need to:

### Register as a developer on Memsource website

So you will receive your:
    - *client id* 
    - *client secret*

### Get an Authorization Code

```php
$memsource = new \BrunoFontes\Memsource();
$url = $memsource->oauth()->getAuthorizationCodeUrl($cliend_id, $callback_uri);
```

Redirect your browser to this returned `$url` so the user can login via *oauth*. 

The `$callback_uri` will be called by **Memsource** with a `$_GET['code']` that contains your Authorization Code, which you can use to...

### Get an Access Token

```php
$authCode = $_GET['code'];
$memsource = new \BrunoFontes\Memsource();
$token = $memsource->oauth()->getAccessToken($authCode, $client_id, $client_secret, $callback_uri);
```

Safely store this `$token` with the related user data and use it on any 

## Project

### Project list

To list all projects...

```php
$memsource = new \BrunoFontes\Memsource();
$projectList = $memsource->project()->listProjects;
```

To use filters, add the API filter as parâmeter:

```php
$projectList = $memsource->project()->listProjects(['name' => 'Project X']);
```

## Bilingual Files

