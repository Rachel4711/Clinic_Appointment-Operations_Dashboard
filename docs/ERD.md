# Database ERD

```mermaid
erDiagram
  USERS ||--o| DOCTORS : "may have profile"
  USERS ||--o{ APPOINTMENTS : creates
  USERS ||--o{ ISSUE_REPORTS : reports
  USERS ||--o{ ISSUE_REPORTS : assigned
  USERS ||--o{ ACTIVITY_LOGS : performs
  DOCTORS ||--o{ APPOINTMENTS : attends
  PATIENTS ||--o{ APPOINTMENTS : books

  USERS { bigint id PK string name string email UK string password enum role boolean is_active }
  DOCTORS { bigint id PK bigint user_id FK string name string specialisation string phone string email UK enum availability_status }
  PATIENTS { bigint id PK string patient_number UK string full_name date date_of_birth enum gender string phone string email text address string emergency_contact }
  APPOINTMENTS { bigint id PK bigint patient_id FK bigint doctor_id FK date appointment_date time start_time time end_time string reason enum status text notes bigint created_by FK }
  ISSUE_REPORTS { bigint id PK bigint reported_by FK string title text description enum priority enum status bigint assigned_to FK text resolution_notes timestamp resolved_at }
  ACTIVITY_LOGS { bigint id PK bigint user_id FK string action string entity_type bigint entity_id text description }
```

Appointments and historical records use restrictive deletes. Optional user/profile and assignee references become null when the related account is removed. Search and filter columns are indexed.
