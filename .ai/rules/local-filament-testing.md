---
paths:
  - app/Filament/**
  - app/Providers/Filament/**
  - database/seeders/**
  - resources/views/**
  - routes/**
  - tests/**
---

# Local Filament Browser Verification

For local/admin browser verification only, use the project's existing standard local Filament test-account mechanism created by `DatabaseSeeder` when authentication is required.

The account is not a production account. Do not create, copy, print, log, commit, or persist its credentials in repository rules, documentation, environment files, test artifacts, screenshots, or external services. Obtain the credentials from the active local-account mechanism or the current task context when needed.

Never use this local test account or passwordless/local authentication configuration for production verification or production access.
