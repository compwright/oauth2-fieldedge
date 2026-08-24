# FieldEdge Provider for OAuth 2.0 Client

[![Build Status](https://github.com/compwright/oauth2-fieldedge/actions/workflows/php.yml/badge.svg)](https://github.com/compwright/oauth2-fieldedge/actions/workflows/php.yml)
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

## Testing

```bash
$ make test
```

## Upgrading

Please see [UPGRADING](https://github.com/compwright/oauth2-fieldedge/blob/master/UPGRADING.md) for instructions on upgrading from previous versions (1.x, 2.x).

## Contributing

Please see [CONTRIBUTING](https://github.com/compwright/oauth2-fieldedge/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](https://github.com/compwright/oauth2-fieldedge/blob/master/LICENSE) for more information.
