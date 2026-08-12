# FlightPHP and NGINX

This directory contains an alternative container image that runs the [FlightPHP](https://docs.flightphp.com/) application behind **NGINX + PHP-FPM** instead of the built-in PHP development server.

## Overview

| Component | Role |
|-----------|------|
| `php-fpm` | Executes PHP scripts, listens on `127.0.0.1:9000` |
| `nginx` | Reverse proxy / static file server, listens on port `8000` |

The two processes are started by `entrypoint-nginx.sh`: php-fpm is launched as a daemon, then nginx runs in the foreground so Docker can track the container lifecycle.

## Files

| File | Description |
|------|-------------|
| `Dockerfile.nginx` | Multi-stage image — copies app source from the parent build context (`myapp`), installs Composer dependencies, and bundles nginx |
| `nginx.conf` | Nginx configuration: serves `src/public` on port `8000`, forwards `.php` requests to php-fpm via FastCGI |
| `entrypoint-nginx.sh` | Container entrypoint — creates required temp directories, starts php-fpm, then starts nginx |
| `Makefile` | Convenience targets for building, pushing, and running the image locally |

## Build & Run

### Build the image

```bash
make docker_build
```

The build requires the parent directory as an additional build context (`myapp`):

```bash
docker buildx build \
  --build-context myapp=.. \
  --platform linux/amd64 \
  --file Dockerfile.nginx \
  --tag custom_container_event_flightphp_php_nginx:latest .
```

### Run locally

```bash
make docker_run_local
```

The container is exposed on `http://localhost:8000`.

### Test locally

```bash
make test_local
```

Sends a sample FunctionGraph event to `POST /invoke`:

```bash
curl -X POST \
  -H "X-Cff-Request-Id: <uuid>" \
  -H "X-Cff-Func-Name: 0@default@sample-container-event-flightphp" \
  -H "X-Cff-Func-Version: latest" \
  -H "X-Cff-Func-Timeout: 30" \
  -H "X-Cff-region: eu_de" \
  -H 'Content-Type: application/json' \
  -d '{"key":"Hello World of FunctionGraph"}' \
  localhost:8000/invoke
```

### Push to OTC SWR

```bash
make docker_push
```

Requires the following environment variables:

| Variable | Description |
|----------|-------------|
| `OTC_SWR_LOGIN_KEY` | Long-term login key from OTC SWR |
| `OTC_SDK_PROJECTNAME` | OTC project name |
| `OTC_SDK_AK` | OTC access key |
| `OTC_SWR_ENDPOINT` | SWR registry endpoint (e.g. `swr.eu-de.otc.t-systems.com`) |
| `OTC_SWR_ORGANIZATION` | SWR organization / namespace |

See the [OTC SWR documentation](https://docs.otc.t-systems.com/software-repository-container/umn/image_management/obtaining_a_long-term_valid_login_command.html) for how to obtain a long-term login key.

## Security Notes

- The container runs as a non-root user (`paas_user`, UID/GID `1003`).
- All nginx temp paths are redirected to `/tmp` so no writes are needed outside the user's home directory.
- The `.ht*` location block denies access to hidden Apache-style config files.
