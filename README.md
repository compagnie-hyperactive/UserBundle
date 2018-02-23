# LCH User Bundle

This bundle provide a minimalistic yet configurable user management fonr Symfony applications.

## Installation

```
composer require lch/user-bundle
```

## Configuration

In a dedicated `lch_user.yaml` (for SF 3.4+ with flex) or classical `config.yml`, add following keys:
```yaml
lch_user:
  resetting_ttl: 86400 # optional  
  classes:
    user: App\Entity\User\User # Mandatory
    manager:  App\Manager\UserManager # optional
    mailer:  App\Util\Mailer # optional
    token_generator:  App\Util\TokenGenerator # optional    
```

This bundle provides utilities. This mean all rendering (templates, presentation, but also routing...) are up to you and you project specifities. 

## Usage

On your app, go to
* `/admin` : login form, with reset password link
* `/registration` : simple registration form

## Route override
Feel free to duplicate and edit `@LchUserBundle/Resources/config/routing.yml` to make you own routes edition.

Completely work in progress. See [Roadmap]() to help.