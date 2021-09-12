# Limiter for Mautic 3 and Mautic 2

This plugin works on Mautic 4 and Mautic 3 as well. Download link for Mautic 2 version is included in the package in `mautic2-version.md` file.

This plugin disable Mautic features after extend limit of number of identified contacts. You can hide create new contact, new email, campaigns or editation too.

## Installation

### Manual

1. Use last version
2. Unzip files to plugins/MauticLimiterBundle
3. Clear cache (app/cache/prod/)
4. Go to /s/plugins/reload

### Usage

Configure Limiter from config (app/config/local.php) 

```php
'limiter'                               => [
    'limit'   => 5000, 
    'routes'  => [
         '*campaigns/new',
         '*campaigns',
         '*contacts/new',
         '*contacts/edit/*',
    ],
    'message' => '<h3>Contacts limit: {numberOfContacts}/{actualLimit}</h3><p>You have reached the limit  of contacts. <a href="bttps://mtcextendee.com/contact"><strong>contact support</strong></a></p>',
    'style'=>'.alert-limiter-custom { background:red; color:#fff; }',
    'api_secret_key' => 'some hash'
]
```

#### Parameters

- limit = number for identified contacts to stop (0 means unlimited)
- message - your message (allow HTML)
- routes - array of url routes with wildcard
- style - css style for alert message (class .alert-limiter-custom)
- api_secret_key - add API secret key If you want use API. This key would be validate from request

**Every change require clear cache (app/cache/prod/)**  

#### Tokens

You can use in message these tokens

- {numberOfContacts}
- {actualLimit}

## API

#### GET

```
$api = new \Mautic\Api\Api($auth, $apiUrl);
$response = $api->makeRequest('limiter/get'); // get all settings
$response = $api->makeRequest('limiter/message/get'); // get message setting
$response = $api->makeRequest('limiter/limit/get'); // get limit setting
$response = $api->makeRequest('limiter/routes/get'); // get routes setting
$response = $api->makeRequest('limiter/routes/style'); // get style setting
```

Response

```
Array
(
    [response] => Any response from your request
)
```

#### UPDATE
```
$api = new \Mautic\Api\Api($auth, $apiUrl);
$response = $api->makeRequest(
    'limiter/message/update',
    [
        'message' => 'My custom message',
        'api_secret_key' => 'somehash'
    ],
    'POST'
);
$response = $api->makeRequest(
    'limiter/style/update',
    [
        'style' => '.alert-limiter-custom { background:red } ',
        'api_secret_key' => 'somehash'
    ],
    'POST'
);
$response = $api->makeRequest(
    'limiter/limit/update',
    [
        'limit' => 1000,
        'api_secret_key' => 'somehash'
    ],
    'POST'
);
$response = $api->makeRequest(
    'limiter/routes/update',
    [
        'routes' => [
           		'*contacts/new',
           		'*contacts/edit*',
           		'*campaigns/edit*'
        ],
        'api_secret_key' => 'somehash'
    ],
    'POST'
);
```


Response

```
Array
(
    [success] => 1
)
```

## More Mautic stuff

- https://mtcextendee.com/

### Credits

Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a>
