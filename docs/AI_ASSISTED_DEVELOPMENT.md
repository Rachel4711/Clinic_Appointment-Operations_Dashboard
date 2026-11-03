# AI-assisted development record

## Assisted tasks

AI assisted with repository scaffolding, schema design, Laravel models/controllers/routes, React component and page implementation, responsive CSS, fictional seed data, automated tests, and documentation drafting.

## Accepted suggestions

- React/Vite embedded under Laravel `resources/js`, following the requested course-style single-application structure.
- Sanctum cookie/session authentication for a first-party SPA.
- Explicit role middleware plus record scoping for doctor appointments and personal issue reports.
- Server-side time-overlap validation and restrictive deletes for historical appointments.
- SQLite in-memory tests while retaining MySQL as the target runtime database.

## Rejected or modified suggestions

- The class notes’ JWT and Laravel UI/Reactstrap patterns were not copied because they conflict with the requested Vite/plain-CSS stack and first-party session security.
- The exact early Laravel 8.2 application skeleton was changed to the PHP 8-compatible Laravel 8.6 skeleton; the installed PHP 8.3 runtime cannot satisfy the former’s PHP constraint.
- The initial split `frontend`/`backend` layout was later consolidated into a single Laravel root at the developer’s request.
- Heavy admin templates, chart packages, state libraries, and calendar dependencies were avoided.
- No Lighthouse score or browser screenshot was invented when those measurements were not performed.

## Review and verification

- PHP feature tests exercise login success/failure, unauthenticated access, role denial, patient validation, appointment creation/conflicts/status changes, issue creation, and dashboard access.
- Laravel routes were inspected to confirm authentication and role middleware placement.
- The frontend was linted and production-built; lazy chunks were emitted successfully.
- Environment templates were reviewed to avoid committed secrets.
- Seed data and credentials are clearly fictional and use reserved `.test` domains.

## Developer decisions

The developer remains responsible for dependency upgrades, production threat modeling, accessibility testing with assistive technology, MySQL/Wamp configuration, and determining whether future requirements justify a supported framework upgrade.
