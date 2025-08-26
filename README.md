# 🚧 Work in Progress 🚧

**This project is currently under active development.**

Features may be incomplete or subject to change.

**It is not recommended for production use.**

# 💻 Hard Framework

**Hard** is a lightweight, object-oriented framework written in pure PHP.
It focuses on simplicity, strict typing, and clean architecture principles (SOLID, KISS, DRY).

The goal of **Hard**? Peek behind the curtain of web frameworks, pull them apart, and see if I can make the magic happen
myself.

---

## 🚀 Features

* Strict typing across the entire codebase
* Simple `Router` with support for `GET`, `POST`, `PUT`, `PATCH`, `DELETE`
* Robust error handling for `404 Not Found` and `405 Method Not Allowed`
* Dynamic URL parameters (e.g., `/users/{id}`)
* Effortless Redirects
* Request and Response abstractions
* Contracts for request handlers
* Simple file-based logging

---

## 🔮 Coming soon

* Middleware
* Validation
* And more ...

---

## 📂 Project Structure

```
hard/
├── app/
│   ├── Handlers/        # Your request handlers
│   └── Routes/          # Route definitions (web.php, api.php)
├── infra/
│   ├── Contracts/       # Interfaces and contracts
│   ├── Enums/           # HTTP method enum
│   ├── Exceptions/      # Custom exceptions (NotFound, MethodNotAllowed)
│   └── Http/            # Core HTTP classes
│       └── Handlers/    # Core handlers (NotFound, Redirect, MethodNotAllowed)
├── public/
│   └── index.php        # Front controller
├── storage/
│   └── logs/            # Log files
├── tests/               # Test suite
└── vendor/              # Composer dependencies
```

---

## ⚡ Installation

Clone the repository:

```bash
git clone https://github.com/clebsonsh/hard-framework.git
cd hard
composer install
```

Run a development server:

```bash
composer dev
```

Visit 👉 [http://localhost:8000](http://localhost:8000)

---

## 🛠 Usage

### Defining Routes

Inside `app/Routes/web.php`:

```php
use App\Handlers\AboutHandler;
use App\Handlers\HomeHandler;
use Infra\Http\Router;

return function (Router $router) {
    // The handler is a class string, it will be lazy-loaded.
    $router->get('/', HomeHandler::class);

    // The handler is an instance, it is loaded immediately.
    $router->get('/about', new AboutHandler);
};
```

### Defining Routes with Parameters

You can define dynamic segments in your routes by wrapping a parameter name in curly braces `{}`. The router will
automatically extract the value from the URL.

```php
use App\Handlers\UserHandler;
use Infra\Http\Router;

return function (Router $router) {
    // This route will match URLs like /users/42 or /users/clebson
    $router->get('/users/{id}', new UserHandler);
};
```

### Defining Redirects

Easily create redirects with a specific HTTP status code.

```php
use Infra\Http\Router;

return function (Router $router) {
    // Redirects /old-url to /new-url with a 301 status code
    $router->redirect('/old-url', '/new-url', 301);
};
```

### Writing a Handler

All handlers implement `RequestHandlerInterface`. You can access route parameters from the `Request` object.

```php
namespace App\Handlers;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

class UserHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        // Retrieve the 'id' parameter from the URL
        $userId = $request->getParam('id');

        return new Response::html("<p>Showing user: {$userId}<p>");
    }
}
```

### Logging

The framework provides a simple file-based logging system. You can use the following helper functions to write messages to the log file:

* `info(string $message)`
* `debug(string $message)`
* `warn(string $message)`
* `error(string $message)`

Logs are stored in the `storage/logs` directory, with a new file created each day.

Example:
```php
// in any part of your application
info('This is an informational message.');

try {
    // some operation that might fail
} catch (Exception $e) {
    error((string) $e);
}
```

---

## ✅ Example Workflow

1. Define a handler in `app/Handlers`
2. Register it in a route file (`web.php` or `api.php`)
3. Run the server and visit the route

---

## 🧪 Testing

This project uses [Pest](https://pestphp.com/) for testing. The test suite provides 100% unit test coverage for the core
framework components in the `infra/` directory.

To run the entire test suite, use the following Composer script:

```bash
composer test
```

This will execute all tests located in the `tests/` directory.

---

## 🔬 Static Analysis

This project uses [PHPStan](https://phpstan.org/) for static analysis to find bugs before they reach production.

To run PHPStan, use the following Composer script:

```bash
composer lint
```

This will analyze the `app/` ,`infra/` and `public/` directories.

---

## 🤝 Contributing

Pull requests are welcome!
Please open an issue first to discuss what you’d like to change.

---

## 📜 License

MIT License – free to use, modify, and distribute.
