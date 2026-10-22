---
paths:
  - '**'
---

# General

## Recover stale git lock files with npm run recover
If a git command fails with 'Unable to create .git/index.lock: File exists', do not hand-delete the lock. Run `npm run recover` (scripts/git-recover.mjs), which verifies no git process is running and the lock is stale (older than 5 minutes, overridable with --force), then renames the lock with a .stale-<timestamp> suffix so recovery is reversible. Hand-deleting an active lock can corrupt an in-flight operation. If a lock reappears immediately after recovery, something is repeatedly crashing mid-command — investigate that instead of looping recovery.
