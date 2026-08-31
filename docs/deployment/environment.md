# Environment

Required production settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://readora.example.com
LOG_LEVEL=warning
```

Use a managed MySQL/MariaDB database and shared Redis or database-backed cache
and queues when running more than one application instance. Set `SESSION_DOMAIN`
and `SESSION_SECURE_COOKIE` for the deployment domain. Configure exactly one AI
provider with a secret API key; keep all credentials outside version control.

After changing environment values, run `php artisan optimize:clear` followed by
`php artisan optimize`. Do not run `config:cache` until every required setting
has been configured.
