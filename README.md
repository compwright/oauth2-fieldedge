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

Usage is the same as The League's OAuth client, using `\Compwright\OAuth2_Fieldedge\Provider` as the provider.

### Example: Client Credentials Flow

```php
$provider = new Compwright\OAuth2_Fieldedge\Provider([
    'clientId'      => '{fieldedge-client-id}',
    'clientSecret'  => '{fieldedge-api-key}',
]);

// Get an access token
$token = $provider->getAccessToken('client_credentials');

// Use the token to interact with an API on the users behalf
echo $token->getToken();

// The token is really a JWT, getResourceOwnerId() extracts the company_id claim
echo $token->getResourceOwnerId();
```

## Testing

```bash
$ make test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/compwright/oauth2-fieldedge/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Jonathon Hill](https://github.com/compwright)
- [All Contributors](https://github.com/compwright/oauth2-fieldedge/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/compwright/oauth2-fieldedge/blob/master/LICENSE) for more information.
