#!/usr/bin/env node
// Safety script for clearing stale lock files from the repository's .git directory.
//
// Background: when a git process crashes or is killed mid-operation (for example,
// an agent session that dies while committing), it can leave an "index.lock" (or
// similar "*.lock") file behind. An orphaned lock makes every index-writing git
// command fail until the stale file is removed.
//
// Usage: npm run recover [-- [--force] [--max-age-minutes N]]
//
// Safety checks (both must pass before anything is touched):
// - no git process is currently running  (tasklist on Windows, pgrep on POSIX)
// - the lock is older than --max-age-minutes (default: 5 minutes) unless --force
//
// Matching lock files are renamed with a ".stale-<timestamp>" suffix rather than
// deleted, so recovery is reversible. If a lock reappears immediately after a
// recovery, something is repeatedly crashing mid-command: investigate instead
// of looping recovery.

import { execSync, spawnSync } from 'node:child_process';
import { readdirSync, statSync, renameSync } from 'node:fs';
import { join } from 'node:path';

const args = process.argv.slice(2);
const force = args.includes('--force');
const maxAgeFlagIndex = args.indexOf('--max-age-minutes');
const maxAgeMinutes = Number(maxAgeFlagIndex !== -1 ? args[maxAgeFlagIndex + 1] : 5) || 5;

const gitDir = execSync('git rev-parse --absolute-git-dir', { encoding: 'utf8' }).trim();

function isGitProcessRunning() {
    if (process.platform === 'win32') {
        const { status, stdout } = spawnSync('tasklist', ['/FI', 'IMAGENAME eq git.exe', '/NH'], {
            encoding: 'utf8',
        });

        return status === 0 && /git\.exe/i.test(stdout);
    }

    return spawnSync('pgrep', ['-x', 'git']).status === 0;
}

if (isGitProcessRunning()) {
    console.error('A git process is currently running. Refusing to touch lock files.');
    process.exit(1);
}

const lockPaths = readdirSync(gitDir)
    .filter((name) => name.endsWith('.lock'))
    .filter((name) => statSync(join(gitDir, name)).isFile())
    .map((name) => join(gitDir, name));

if (lockPaths.length === 0) {
    console.log('No lock files found in ' + gitDir + '. Nothing to recover.');
    process.exit(0);
}

const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
let recovered = 0;

for (const lockPath of lockPaths) {
    const ageMinutes = (Date.now() - statSync(lockPath).mtimeMs) / 60000;

    if (!force && ageMinutes < maxAgeMinutes) {
        console.error(
            `${lockPath} is only ${ageMinutes.toFixed(1)} minute(s) old. It may belong to a live git process. Re-run later or use --force to override the age check.`,
        );
        continue;
    }

    renameSync(lockPath, `${lockPath}.stale-${timestamp}`);
    recovered += 1;
    console.log(`Recovered: renamed ${lockPath} to ${lockPath}.stale-${timestamp}`);
}

if (recovered === lockPaths.length) {
    console.log('All lock files recovered. You can re-run the failed git command now.');
    process.exit(0);
}

process.exit(1);
