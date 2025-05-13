
# Laravel JWT Authentication Setup

This guide walks through the steps for implementing **JWT (JSON Web Token)** authentication in a Laravel project using the `php-open-source-saver/jwt-auth` package.

---

## 🔧 Installation Steps

### 1. Install Laravel Project (if not already done)

```bash
composer create-project laravel/laravel my-api
cd my-api
```
`php artisan install:api`

### 2. Install JWT Auth Package

```bash
composer require php-open-source-saver/jwt-auth
```

### 3. Publish JWT Config

```bash
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
```

This will publish the `config/jwt.php` file.

### 4. Generate JWT Secret

```bash
php artisan jwt:secret
```

This will set the `JWT_SECRET` in your `.env` file.

---

## ⚙️ Configuration

### 5. Update `config/auth.php`

**Set the default guard to `api`:**

```php
'defaults' => [
    'guard' => env('AUTH_GUARD', 'api'),
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],
```

**Add the `api` guard using `jwt` driver:**

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

---

## 👤 Update the `User` Model

Open `app/Models/User.php` and implement the JWT contract:

```php
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    // Return the identifier to be stored in the JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Add any custom claims to the JWT
    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

---

## ✅ Routes Example (Optional)

You may define routes in `routes/api.php`:

```php
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('user', function () {
    return auth()->user();
});
```

---

## 🛡️ Best Practices

- Store the `JWT_SECRET` securely in environment variables.
- Use HTTPS in production to protect JWTs in transit.
- Always validate and sanitize user input.
- Set token TTL (`ttl`) and refresh TTL (`refresh_ttl`) in `config/jwt.php` as per your app's security policy.
- Use middleware to protect routes: `auth:api`.

---

## 📚 Resources

- Package Docs: [php-open-source-saver/jwt-auth](https://github.com/php-open-source-saver/jwt-auth)
- Laravel Auth Docs: https://laravel.com/docs/authentication

---

## 🧑‍💻 Author

Developed by: **Md. Tarequr Rahman Sabbir**
