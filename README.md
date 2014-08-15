# OAuth 2.0 Server Bundle

OAuth 2.0 server bundle for Symfony 2 framework

[![Build Status](https://secure.travis-ci.org/michalkvasnicak/oauth2-server-bundle.png)](http://travis-ci.org/michalkvasnicak/oauth2-server-bundle)

## Requirements

* PHP >= 5.4
* HHVM

## Installation

Using composer

```json
{
    "require": {
        "michalkvasnicak/oauth2-server-bundle": "*"
    }
}
```

## Configuration

### Basic configuration

These are defaults.

```yaml
oauth2_server.access_tokens:
    lifetime: 1209600 # 14 days lifetime of token (default)

oauth2_server.authorization_codes:
    lifetime: 60 # 60 seconds lifetime of authorization code (used only by authorization code grant type)

oauth2_server.refresh_tokens:
    generate: true # generate refresh tokens (default)
    lifetime: 2678400 # 31 days lifetime of token (default)

# www_realm returned WWW-Authenticate HTTP header if you are unauthenticated
oauth2_server.www_realm: 'OAuth2Server'

# accepted token used to sign requests
oauth2_server.classes.token_type: 'OAuth2\TokenType\Bearer'
```

### Storage

You can use michalkvasnicak/OAuth2MongoDBBundle or create own model. If you want to create own model you have to define **user provider** and services needed for grant types you are going to use.

```yaml
# this is needed for authentication
# service has to implement Symfony\Component\Security\Core\User\UserProviderInterface
oauth2_server.user_provider: 'service id'


# STORAGES
oauth2_server.storage:

    # this is needed for authentication and authorization of protected requests
    # also is used by all grant types
    # has to implement OAuth2\Storage\IAccessTokenStorage
    access_token: 'service id'


    # this is needed for client identification
    # also this is used in client credentials grant type
    # has to implement OAuth2\Storage\IClientStorage
    client: 'service id'


    # optional but if you are using authorization code grant type you have to set it
    # has to implement OAuth2\Storage\IAuthorizationCodeStorage
    authorization_code: 'service id'


    # optional but if you are using refresh token grant type or generating refresh tokens
    # you have to set it
    # has to implement OAuth2\Storage\IRefreshTokenStorage
    refresh_token: 'service id'

```

### Grant types

There are pre-installed grant types already. To use them just enable them (by default they are all disabled).

```yaml
oauth2_server.grant_types:
    authorization_code: false
    client_credentials: false
    implicit: true
    refresh_token: true
    resource_owner_password_credentials: true
```

### Own grant types

You can use your own grant types too, just create services and tag them as 'oauth2_server.grant_type'. All services has to implement OAuth2\GrantType\IGrantType.

```yaml
my_custom_grant_type:
    class: My\Own\GrantType
    tags:
        - { name: oauth2_server.grant_type }
```

## TODO:

* Authorization endpoint