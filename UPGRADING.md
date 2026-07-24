# Upgrading

## 1.x to 2.x

Version 2.x represents a complete re-write which breaks from previous versions. The new implementation is simpler, relies on fewer dependencies, and leverages language features introduced in PHP 8.

### Updated PHP requirement

The minimum PHP version required is 8.3.

### Namespace and class name changes

* Package namespace has changed from `CompWright\OAuth2_Housecallpro` to `CompWright\OAuth2\FieldEdge`
* `Provider` and `ResourceOwner` classes have been removed

### New factory class

A factory class has been introduced, use `ProviderFactory::new()` (or `newLegacy()`) to set up a new provider class instance.

### Dropped support for resource owner

Resource owner is no longer supported. The access token provided by FieldEdge is in JWT format, you can parse that using a suitable library to obtain the resource owner ID.
