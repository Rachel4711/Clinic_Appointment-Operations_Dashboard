# REST API reference

Base URL: `http://localhost:8000/api`. Send `Accept: application/json`. The SPA first calls `GET /sanctum/csrf-cookie`, then sends the `X-XSRF-TOKEN` header and session cookie on writes.

Successful single-resource responses use `{ "data": ..., "message": "..." }`. Lists use Laravel pagination (`data`, `current_page`, `last_page`, `per_page`, `total`). Validation failures return HTTP 422 with `message` and field-keyed `errors`; unauthenticated requests return 401, unauthorized requests 403, and missing records 404.

## Authentication

| Method | Endpoint | Access | Purpose |
|---|---|---|---|
| POST | `/login` | Public, rate limited | Login with `email`, `password` |
| GET | `/user` | Authenticated | Current user |
| POST | `/logout` | Authenticated | Invalidate session |

## Operational endpoints

| Method | Endpoint | Roles | Notes |
|---|---|---|---|
| GET | `/dashboard` | All | Role-scoped counts, statuses, recent records |
| GET | `/patients` | Admin, receptionist | `search`, `page`, `per_page`, `sort`, `direction` |
| POST | `/patients` | Admin, receptionist | Create patient |
| GET | `/patients/{id}` | Admin, receptionist | Details with appointment history |
| PATCH | `/patients/{id}` | Admin, receptionist | Update patient |
| DELETE | `/patients/{id}` | Admin, receptionist | Rejected when history exists |
| GET | `/doctors` | All | `search`, `availability_status`, pagination |
| GET | `/doctors/{id}` | All | Doctor detail |
| POST/PATCH/DELETE | `/doctors[/{id}]` | Admin | Manage doctors |
| GET | `/appointments` | All | `date`, `doctor_id`, `patient_id`, `status`, pagination; doctors see their own |
| POST | `/appointments` | Admin, receptionist | Overlap conflicts return 422 on `start_time` |
| GET | `/appointments/{id}` | All, scoped | Appointment detail |
| PATCH | `/appointments/{id}` | All, scoped | Doctors may update only `status` and `notes` |
| DELETE | `/appointments/{id}` | Admin, receptionist | Soft workflow: sets status to Cancelled |
| GET | `/issue-reports` | All | Admin sees all; others see own. Filters: `priority`, `status`, `reported_by`, `assigned_to` |
| POST | `/issue-reports` | All | Create personal report |
| GET/PATCH | `/issue-reports/{id}` | Owner or admin | Admin may assign, resolve, and set resolution notes |
| GET | `/users` | Admin | `search`, `role`, pagination |
| POST/PATCH | `/users[/{id}]` | Admin | Create account, change role/status, set demo password |
| GET | `/activity-logs` | Admin | `user_id`, `action`, `entity_type`, pagination |

## Appointment payload

```json
{
  "patient_id": 1,
  "doctor_id": 1,
  "appointment_date": "2026-11-03",
  "start_time": "09:00",
  "end_time": "09:30",
  "reason": "Routine follow-up",
  "status": "Scheduled",
  "notes": null
}
```

Allowed statuses: `Scheduled`, `Checked In`, `In Progress`, `Completed`, `Cancelled`, `No Show`.

## Issue payload

```json
{
  "title": "Waiting room display delay",
  "description": "The fictional display refresh is delayed.",
  "priority": "Medium"
}
```

Priorities are `Low`, `Medium`, `High`, `Urgent`. Statuses are `Open`, `In Progress`, `Resolved`, `Closed`.
