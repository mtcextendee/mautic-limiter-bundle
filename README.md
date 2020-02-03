# Limiter for Mautic

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
    'message' => 'Your message. <a href="bttps://mtcextendee.com/contact">contact us</a>',
    'routes'  => [
        'mautic_campaign_action' => [
            'objectAction' => 'new',
        ],
        'mautic_email_action' => [
            'objectAction' => 'new',
        ],
        'mautic_contact_action' => [
            'objectAction' => 'new',
        ],
    ],
]
```

#### Parameters

- limit = number for identified contacts to stop (0 means unlimited)
- message - your message (allow HTML)
- routes - array of routes defined in Mautic configuration.  You can use also 

```php
'mautic_campaign_action' => [
    'objectAction' => 'edit',
],
```

Every change require clear cache (app/cache/prod/)

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
        'limit' => 'My custom message'
    ],
    'POST'
);
$response = $api->makeRequest(
    'limiter/limit/update',
    [
        'limit' => 1000
    ],
    'POST'
);
$response = $api->makeRequest(
    'limiter/routes/update',
    [
        'routes' => [
            'mautic_campaign_action' => [
                'objectAction' => 'new',
            ],
        ],
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

- Plugins from Mautic Extendee Family  https://mtcextendee.com/plugins
- Mautic themes https://mtcextendee.com/themes

### Credits

Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a>
