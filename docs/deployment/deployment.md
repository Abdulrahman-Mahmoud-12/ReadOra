# Deployment

## Production Checklist

1. Provision PHP 8.4, Composer, Node.js, MySQL/MariaDB, and a web server.
2. Deploy the repository without `.env`, `vendor/`, or `node_modules/`.
3. Run `composer install --no-dev --optimize-autoloader`.
4. Copy `.env.example` to `.env`, set production credentials, and run
   `php artisan key:generate` only for a new installation.
5. Run `php artisan migrate --force` and configure storage permissions.
6. Run `npm ci` and `npm run build`.
7. Run `php artisan optimize` and configure the scheduler with
   `php artisan schedule:run` every minute.
8. Run queue workers if `QUEUE_CONNECTION` is not `sync`.
9. Configure HTTPS, database backups, log rotation, and process monitoring.

The web server document root must be the `public/` directory. Never serve the
repository root, `.env`, storage logs, or source files directly.
