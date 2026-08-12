# container-event-flightphp

Sample showing how to use the [Flight PHP Framework](https://docs.flightphp.com/en/v3/) as an OTC FunctionGraph **event function** triggered by a **Timer**, deployed as a container image.

Inspired by [flightphp/skeleton](https://github.com/flightphp/skeleton).

## Overview

The function is invoked via an HTTP POST to `/invoke`. FunctionGraph passes the timer event as a JSON body. The `FGController` parses it with the `TimerEvent` model and returns a JSON response.

```
Timer Trigger ──► FunctionGraph ──► POST /invoke ──► FGController::invoke()
                                    POST /init   ──► FGController::init()
```

The container runs PHP's built-in HTTP server (`php -S 0.0.0.0:8000`) serving `src/public/`. For a production-grade alternative using NGINX + PHP-FPM see the [`nginx/`](nginx/) subdirectory.

## Project Structure

```
.
├── Dockerfile            # Container image (php:8.2-zts-alpine, non-root user, port 8000)
├── entrypoint.sh         # Starts the PHP built-in server
├── composer.json         # Dependencies: flightphp/core, monolog/monolog
├── src/
│   ├── public/           # Document root (index.php + Flight bootstrap)
│   └── app/
│       ├── config/       # Flight app configuration
│       ├── controllers/  # FGController (init + invoke handlers)
│       ├── middlewares/  # CFF header middleware
│       └── processors/   # Monolog processors
├── resources/
│   └── test_event.json   # Sample TIMER trigger event payload
├── terraform/            # OpenTofu / Terraform IaC for OTC deployment
├── Makefile              # Docker targets
├── MakefileTF            # Terraform targets (includes Makefile)
└── nginx/                # Alternative NGINX + PHP-FPM image
```

## Prerequisites

- Docker with BuildKit / `buildx`
- Composer (only needed outside Docker)
- OpenTofu or Terraform ≥ 1.x (for deployment)
- OTC credentials exported as environment variables (see below)

## Required Environment Variables

| Variable | Description |
|----------|-------------|
| `OTC_SDK_REGION` | OTC region (e.g. `eu-de`) |
| `OTC_SDK_PROJECTID` | OTC project ID |
| `OTC_SDK_PROJECTNAME` | OTC project name |
| `OTC_SDK_DOMAIN_NAME` | OTC domain name |
| `OTC_SDK_AK` | OTC access key |
| `OTC_SDK_SK` | OTC secret key  |
| `OTC_SWR_ENDPOINT` | SWR registry endpoint (e.g. `swr.eu-de.otc.t-systems.com`) |
| `OTC_SWR_LOGIN_KEY` | Long-term login key from OTC SWR |
| `OTC_SWR_ORGANIZATION` | SWR organization / namespace |
| `OTC_IAM_ENDPOINT` | IAM endpoint (e.g. `https://iam.eu-de.otc.t-systems.com/v3`) |
| `AWS_REQUEST_CHECKSUM_CALCULATION` | `when_required` |
| `AWS_RESPONSE_CHECKSUM_VALIDATION` | `when_required` |


See the [OTC SWR documentation](https://docs.otc.t-systems.com/software-repository-container/umn/image_management/obtaining_a_long-term_valid_login_command.html) for how to obtain a long-term login key.

## Build & Run Locally

### Build the image

```bash
make docker_build
```

### Run locally

```bash
make docker_run_local
```

The container is available at `http://localhost:8000`.

### Test locally

```bash
make test_local
```

Sends the sample timer event from `resources/test_event.json` to `POST /invoke`:

```json
{
  "version": "v1.0",
  "time": "2023-06-01T08:30:00+08:00",
  "trigger_type": "TIMER",
  "trigger_name": "Timer_001",
  "user_event": "{\"message\": \"timer triggered event\", \"topic\":\"test\"}"
}
```

### Push to OTC SWR

```bash
make docker_push
```

## Deploy to OTC FunctionGraph

All Terraform targets are in `MakefileTF`, which includes `Makefile` so Docker targets are also available.

### Plan

```bash
make -f MakefileTF tf_plan
```

### Deploy (build → push → apply)

```bash
make -f MakefileTF tf_apply
```

### Apply without re-pushing the image

```bash
make -f MakefileTF tf_apply_no_push
```

### Destroy

```bash
make -f MakefileTF tf_destroy
```

### Test the deployed function

```bash
make -f MakefileTF test_deployed
```

Retrieves an auth token via `utils/tokenFromUsername.sh`, reads the function URN from the Terraform output, and calls the FunctionGraph invocation API directly.

## Terraform Resources

The `terraform/` directory provisions:

| Resource | Description |
|----------|-------------|
| `function.tf` | FunctionGraph function (container image runtime) |
| `timer_trigger.tf` | Timer trigger |
| `loggroup.tf` | LTS log group for function logs |
| `agency.tf` | IAM agency granting the function necessary permissions |
| `testevent.tf` | Saved test event in FunctionGraph console |
| `variables.tf` / `variables.tfvars` | Input variables (prefix, function name, tags) |

State is stored in an OTC OBS bucket configured via `MakefileTF` backend variables.

## Security Notes

- The container runs as a non-root user (`paas_user`, UID/GID `1003`).
- The image uses a pinned digest for both the `composer` and `php` base images.
- No secrets are baked into the image; credentials are supplied at runtime via environment variables.
