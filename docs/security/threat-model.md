# Threat Model

| Threat                           | Mitigation                                                                      |
| -------------------------------- | ------------------------------------------------------------------------------- |
| Credential leakage               | Environment configuration, secret filtering, rotation procedure                 |
| AI prompt injection              | Backend authorization and allowlisted public context; model is not an authority |
| AI abuse or cost spikes          | Per-user/IP rate limiter on AI endpoints                                        |
| Stolen API token                 | SHA-256 storage, six-month expiry, explicit revocation                          |
| Privilege escalation             | `auth` and `admin` middleware with server-side role checks                      |
| Browser framing and MIME attacks | `X-Frame-Options` and `X-Content-Type-Options` headers                          |
| Cross-site request forgery       | Laravel web middleware and Blade CSRF tokens                                    |
| Borrowing race conditions        | Transactional service logic and row-level locking                               |

Residual risks include the minimal token system having no abilities/scopes and
the AI prototype not yet supporting private user or admin context.
