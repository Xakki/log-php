# Changelog

## [0.8.5] - 2026-11-05

### Changed

- **BC: Composer package renamed** `xakki/phperrorcatcher` → `xakki/log-php`; the repository moved to
  `Xakki/log-php`.
- **BC: PHP namespace renamed** `Xakki\PhpErrorCatcher` → `Xakki\Log\Php` (class names unchanged,
  e.g. `Xakki\Log\Php\PhpErrorCatcher`). Update `use` statements and `composer.json`.
- README: links to the sibling libraries (log-laravel, log-symfony, log-python, log-fluent).
- Trace-exclude list also skips `log-php` vendor frames; `PhpErrorCatcher::VERSION` synced with the package version.
- Dependencies updated; CI matrix PHP 8.3 / 8.4 / 8.5.

## [0.8.4] - 2026-06-12

### Changed

- **BC: PdoStorage rewritten** — writes the canonical record schema
  (`buildRecord()`: datetime, level, level_name, channel, message, context, extra, host, url);
  supports mysql/mariadb, postgresql, sqlite.
- Default table name changed from `_myprof` to `php_error_log`.
- `getPdo()` now accepts: a ready `PDO` object, an array of connection params
  (`engine/host/port/dbname/username/passwd`; sqlite uses `engine`+`path`), or
  a `callable` returning a `PDO` (lazy connect).
- Removed legacy profiler viewer code (`viewRenderBD()`, `createDB()`).
- `PdoStorage` removed from PHPStan `excludePaths`; passes level 8.
- Version bumped in `composer.json` and `package.json`.
