# php8-symfony-demo

This is a work in progress Symfony PHP8 demo that I use to play around with Symfony. It currently sends an example e-mail.

# How to get started

- Add `127.0.0.1 symfony.localhost` to your `/etc/hosts`
- Run `make start`. Docker will now setup the environment.
- Open your browser on `http://symfony.localhost:1337/emails`
- Triggering an e-mail can be done with the Open API spec `/api/open-api.yml` or the Postman collection `docs/Emails.postman_collection.json`.

# ENV

Sign up for Mailtrap and replace this string with your credentials or use another provider. See https://symfony.com/doc/current/mailer.html#using-built-in-transports.
```
MAILER_DSN=smtp://xxxxxx:xxxxxxx@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login
```

# Todo's
- The postgresQL DB connection setup is done and reachable but this project contains a simple dummy model ( Movie ) which is not used yet.
- Unit tests
- Define error responses in open-api spec.
- Create production Docker
- Extend simple twig email
