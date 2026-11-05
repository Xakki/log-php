# log-php (PHPErrorCatcher)

Logger for all PHP & JS errors (PSR-3), with pluggable storage backends.
Requires **PHP 8.3+**. Full runnable example:
[`example/test.php`](https://github.com/Xakki/log-php/blob/master/example/test.php).

📖 **[Documentation site](https://xakki.github.io/log-php/)**

## Install

```bash
composer require xakki/log-php
```

## Init

Instantiate with named arguments. The constructor registers all
error/exception/shutdown handlers and sets `static::$obj` (accessible as a
singleton via `PhpErrorCatcher::init()` for legacy code):

```php
use Xakki\Log\Php\PhpErrorCatcher;

require_once 'vendor/autoload.php';

$logger = new PhpErrorCatcher(
    storage: [
        // any subset of: FileStorage, StreamStorage, SyslogStorage, ElasticStorage
        'StreamStorage' => ['stream' => 'php://stderr'],
    ],
    dirRoot:   __DIR__,
    debugMode: false,
);
```

> **Deprecated:** `PhpErrorCatcher::init(array $config)` still works for
> backward compatibility but will be removed in a future release. Prefer
> `new PhpErrorCatcher(...)` with named arguments.

From here every uncaught error, exception and `trigger_error()` is captured
automatically.

## Custom reporting

The instance implements `Psr\Log\LoggerInterface`, so log manually with the
standard PSR-3 methods. An exception can be passed straight as the message:

```php
$logger->error('My custom error');     // custom message
$logger->critical($exception);         // log a caught \Throwable
trigger_error('msg', E_USER_WARNING);  // also captured by the handler
```

## Browser (JS) errors

`JsLogPlugin` catches browser errors (`window.onerror`, `console.error`,
`unhandledrejection`, failed resource loads) and reports them to the PHP side.
It skips bots/crawlers and noise (cross-origin `"Script error."`, browser
extensions), de-duplicates, and attaches context (viewport, build version,
anonymized breadcrumbs).

There are two ways to include the script.

**Static** — serve the bundled file as-is. The trigger key must match the client
default `catcherLogName`, so set `initGetKey => 'catcherLogName'`:

```html
<script src="src/catcher.js"></script>
<script>errorCatcher('custom error');</script>
```

**Dynamic (recommended)** — let `JsLogPlugin` serve the script with the trigger
key (and an optional signed token) injected, so any `initGetKey` works. The
response is cached for one day:

```html
<script src="/catcher.js"></script>
```

An optional `secret` in the plugin config enables a stateless HMAC token: the
served script embeds a short-lived token that the server verifies on every log
(no storage needed). Leave it empty to gate access by `initGetKey` only.

## Web log viewer

When errors are persisted with `FileStorage`, `FileViewer` renders an HTML
report to browse, download, back up and delete the stored log files. Register
it via the `viewer` config and gate access with its own `initGetKey`:

```php
'viewer' => [
    'class'      => 'FileViewer',
    'initGetKey' => 'changeMe5',
],
```

Then open the entry point with that key as a query parameter, e.g.
`/?changeMe5=`.

## Related projects

Sibling logging libraries sharing one field contract ([Logging Rules](https://github.com/Xakki/log-laravel/blob/main/docs/LoggingRules.md)):

* [log-laravel](https://github.com/Xakki/log-laravel) — `xakki/log-laravel`. Laravel / Monolog: custom `LogManager`, processors, redaction, SQL and queue logging.
* [log-symfony](https://github.com/Xakki/log-symfony) — `xakki/log-symfony`. PSR-3 / Symfony / Monolog 3: context enrichment, type coercion, redaction.
* [log-python](https://github.com/Xakki/log-python) — `xakki-log-python` (PyPI). Python: structured JSON logging with the same field/type contract.
* [log-fluent](https://github.com/Xakki/log-fluent) — `xakki/log-fluent`. Fluent Bit config bundle that ships these logs to Graylog (GELF) and Prometheus.
