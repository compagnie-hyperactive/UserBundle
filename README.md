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
  classes:
    user: App\Entity\User\User # Mandatory
    manager:  App\Manager\UserManager # optional
    mailer:  App\Util\Mailer # optional
    token_generator:  App\Util\TokenGenerator # optional
  resetting_ttl: 86400 # optional
  templates: # optional
    login: '@App\security\login.html.twig' # optional
    registration: '@App\security\registration.html.twig' # optional
    reset_password: '@App\security\reset_password.html.twig' # optional
    check_email: '@App\security\reset_password.html.twig' # optional
```

## Usage

On your app, go to
* `/admin` : login form, with reset password link
* `/registration` : simple registration form

Completely work in progress. See [Roadmap]() to help.