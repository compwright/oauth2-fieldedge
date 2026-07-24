# FieldEdge Provider for OAuth 2.0 Client

[![Latest Version](https://img.shields.io/github/release/compwright/oauth2-fieldedge.svg?style=flat-square)](https://github.com/compwright/oauth2-fieldedge/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/compwright/oauth2-fieldedge.svg?style=flat-square)](https://packagist.org/packages/compwright/oauth2-fieldedge)

This package provides FieldEdge OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require compwright/oauth2-fieldedge league/oauth2-client
```

## Usage

Usage is the same as The League's OAuth client, using `\Compwright\OAuth2\FieldEdge\ProviderFactory` to create the provider instance.

### Example: Client Credentials Flow

```php
$provider = new Compwright\OAuth2\FieldEdge\ProviderFactory()->new(
    clientId: '{fieldedge-client-id}',
    clientSecret: '{fieldedge-client-secret}',
);

// Get an access token
$token = $provider->getAccessToken('client_credentials', ['target_id' => 'my_target_id']);

// Use the token to interact with an API on the users behalf
echo $token->getToken();
```

### Example: Client Credentials Flow (Legacy Partner API)

```php
$provider = new Compwright\OAuth2\FieldEdge\ProviderFactory()->newLegacy(
    clientId: '{fieldedge-client-id}',
    apiKey: '{fieldedge-api-key}',
);

// Get an access token
$token = $provider->getAccessToken('client_credentials');

// Use the token to interact with an API on the users behalf
echo $token->getToken();
```

## Testing

```bash
$ make test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/compwright/oauth2-fieldedge/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](https://github.com/compwright/oauth2-fieldedge/blob/master/LICENSE) for more information.
