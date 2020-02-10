# SPA (Vue 2 & Symfony 4.1)
Separate server/client application. \
This project covers several typical SPA things, like:

* Register / Login / Authentication (JWT) / Resseting
* Simple profile creation
* CORS handling
* Client-side routings
* CSRF / XSS protection (At least I've tried)
* Form unification
* Prerender plugin
* Offline usage
* Re-Captcha verification

Also were added: `sass loader, bs4, js/php linters, php static analyzer, dotenv, phpunit, mocha+chai, e2e`

## Requirements

* docker
* docker-compose

## Installation

Generate your RSA keys and put it in `./api/config/jwt` 
as `private.pem` and `public.pem` approprietly.

## Development

```bash
docker-compose up -d
```

To start an assets watching (inside `app` container):

```bash
npm run watch
```

## Deployment

// TODO
