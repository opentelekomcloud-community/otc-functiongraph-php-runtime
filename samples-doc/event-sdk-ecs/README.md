# event-sdk-ecs

Sample FunctionGraph timer event function to start or stop an ECS instance via the ECS API.

Two variants are provided:

| File | Auth method |
|------|-------------|
| `src/index_aksk.php` | AK/SK signing via `SecurityAccessKey` / `SecuritySecretKey` / `SecurityToken` from an agency (recommended) |
| `src/index_token.php` | Token via `getToken()` from an agency (**currently broken** — see warning in source file) |

## How it works

The function is triggered by a FunctionGraph **Timer** event. The `user_event` field of the timer event controls the action:

- `start` — sends a batch start request to the ECS API (`os-start`)
- `stop` — sends a soft batch stop request to the ECS API (`os-stop`)

The ECS instance to act on is configured via the `ECS_INSTANCE_ID` user data variable.

## Prerequisites

- An OTC account with an ECS instance
- An IAM agency granting FunctionGraph permission to start/stop ECS instances (created by Terraform)

## Configuration

The following variables must be set in `terraform/variables.tfvars`:

| Variable | Description |
|----------|-------------|
| `ECS_INSTANCE_ID` | ID of the ECS instance to start/stop |

The ECS endpoint defaults to `ecs.eu-de.otc.t-systems.com` and can be overridden via the `ECS_ENDPOINT` user data variable.

## Deploy

```bash
# Deploy with Terraform
make tf_apply
```

## Test events

Two pre-configured test events are created by Terraform:

- **start** — triggers `os-start` on the configured instance
- **stop** — triggers a soft `os-stop` on the configured instance

Example start event (`resources/event_start.json`):

```json
{
    "version": "v2.0",
    "time": "2023-06-01T08:30:00+08:00",
    "trigger_type": "TIMER",
    "trigger_name": "Timer_start",
    "user_event": "start"
}
```

## Dependencies

- [`opentelekomcloud-community/otc-functiongraph-php-runtime`](https://github.com/opentelekomcloud-community/otc-functiongraph-php-runtime)
- [`opentelekomcloud-community/otc-api-sign-sdk-php`](https://github.com/opentelekomcloud-community/otc-api-sign-sdk-php)
