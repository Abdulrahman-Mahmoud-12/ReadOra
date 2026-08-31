# Production Checklist

- [ ] `APP_ENV=production` and `APP_DEBUG=false`
- [ ] HTTPS is enabled and `APP_URL` uses `https://`
- [ ] `.env` is excluded from version control
- [ ] Exposed AI keys have been revoked and replaced
- [ ] Database backups and restore verification are configured
- [ ] `php artisan migrate --force` completed successfully
- [ ] `php artisan optimize` completed after environment changes
- [ ] Queue workers and scheduler are monitored where enabled
- [ ] Storage and log directories have correct permissions and rotation
- [ ] Security headers are present on HTTP responses
- [ ] Authentication, authorization, AI, API token, and rate-limit tests pass
- [ ] `composer audit` reports no known vulnerabilities
- [ ] The complete PHPUnit suite passes
