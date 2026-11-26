# .anaya — campaign environment

This directory is the container recipe Anaya uses to build, install and test this
repository in isolation. Anaya's ARMORER generated it during a campaign.

- `Dockerfile` — the `app` image (PHP 8.3, Node 22).
- `compose.yml` — `app` plus its services: `mysql` (docker.io/library/mysql:8.4).

Service wiring (`DB_HOST`, `REDIS_HOST`, …) lives in `compose.yml` and applies **only
inside the containers**; your own `.env` is never read or copied. Values in it are
test-only defaults (from `.env.example` or the service images), never real secrets.

## How Anaya uses it

Anaya syncs the repository into an isolated runner and brings the environment up with
`podman-compose -f .anaya/compose.yml up -d --build`, then runs installs and tests
inside the `app` container. The files are plain Compose, so Docker works too:

```sh
docker compose -f .anaya/compose.yml up -d --build
docker compose -f .anaya/compose.yml run --rm -v "$PWD":/work app bash
# inside: composer install && php artisan test
docker compose -f .anaya/compose.yml down -v
```

## Editing

Edit these files freely. Anaya prefers a committed `.anaya/` and starts every future
campaign from it instead of generating a new one.
