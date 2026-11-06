# MawidCloud (موعد كلاود): entry point for AI sessions

Multi-tenant booking SaaS on Laravel 13 / PHP 8.3 / MySQL 8. The owner talks in Arabic; reply in Arabic.

## Start of every session (in this order, nothing more)
1. `docs/ai-memory/SESSION_HANDOFF.md`: where the last session stopped.
2. `docs/ai-memory/NEXT_TASKS.md`: today's task in the 5-day plan.
3. Only the memory file the task needs: `ARCHITECTURE.md` (files, DB, routes), `DECISIONS.md`, `KNOWN_ISSUES.md`, `TESTING.md`, `PROJECT_OVERVIEW.md`.

Do not read the whole codebase. Search with `rg`, read only the files the task touches.

## Hard rules
- One day of the plan per session. Never start the next day without the owner saying so.
- `main` stays green: work on a branch, run CI, merge only after it passes.
- Never delete files, data, branches, Codespaces or repos without the owner's explicit OK.
- Never store secrets, passwords or real customer data in memory files.
- Update `SESSION_HANDOFF.md` (and any affected memory file) before ending a session.
- Windows Git Bash strips backslashes inside heredocs: write PHP namespaces with the Edit/Write tools, not `cat <<EOF`.

## Commands
- Tests run in GitHub Actions (MySQL). There is no PHP on the owner's PC: push a branch, then `gh run watch`.
- Demo data: `php artisan db:seed --class=DemoSeeder` (blocked in production).
- Backup: `php artisan backup:database`.
