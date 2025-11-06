# JoinMe - Security Hardening Checklist

## Executive Summary

Comprehensive security recommendations for JoinMe platform following OWASP Top 10 and Laravel security best practices.

---

## 1. Authentication & Authorization

### 1.1 Password Security
```php
// config/hashing.php
'bcrypt' => [
    'rounds' => 12, // Increase from default 10
],

// Force strong passwords
'password' => 'required|min:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/',
```

### 1.2 Two-Factor Authentication (2FA)
```bash
composer require pragmarx/google2fa-laravel
```

### 1.3 Session Security
```php
// config/session.php
'lifetime' => 120, // 2 hours
'expire_on_close' => true,
'secure' => true, // HTTPS only
'http_only' => true, // Prevent XSS access
'same_site' => 'strict', // CSRF protection
```

### 1.4 Rate Limiting
```php
// Login attempts
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 attempts per minute

// API endpoints
Route::middleware('throttle:60,1')->group(function () {
    // API routes
});

// Per-user rate limiting
RateLimiter::for('api', function (Request $request) {
    return $request->user()
        ? Limit::perMinute(100)->by($request->user()->id)
        : Limit::perMinute(10)->by($request->ip());
});
```

### 1.5 Account Lockout
```php
// After 5 failed attempts, lock account for 15 minutes
public function login(Request $request)
{
    $key = 'login_attempts_' . $request->ip();
    $attempts = Cache::get($key, 0);

    if ($attempts >= 5) {
        throw new TooManyRequestsException('Account locked for 15 minutes');
    }

    // ... login logic

    if ($failed) {
        Cache::put($key, $attempts + 1, now()->addMinutes(15));
    } else {
        Cache::forget($key);
    }
}
```

---

## 2. Input Validation & Sanitization

### 2.1 Request Validation
```php
// app/Http/Requests/CreateConversationRequest.php
class CreateConversationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|profanity',
            'description' => 'required|string|max:5000|profanity',
            'topic_id' => 'required|exists:topics,id',
            'meeting_url' => 'nullable|url|max:500',
            'max_participants' => 'required|integer|min:2|max:100',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'title' => strip_tags($this->title),
            'description' => strip_tags($this->description, '<p><br><strong><em>'),
        ]);
    }
}
```

### 2.2 XSS Prevention
```php
// Always use Blade escaping
{{ $userInput }} // Escaped
{!! $trustedHtml !!} // Raw (only for trusted content)

// Purify HTML input
composer require mews/purifier
{!! clean($userInput) !!}
```

### 2.3 SQL Injection Prevention
```php
// Always use parameterized queries
DB::table('users')->where('email', $email)->first(); // Safe

// Never concatenate user input
DB::select("SELECT * FROM users WHERE email = '$email'"); // UNSAFE!
```

### 2.4 File Upload Security
```php
public function uploadAvatar(Request $request)
{
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $file = $request->file('avatar');

    // Verify MIME type
    if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
        throw new ValidationException('Invalid file type');
    }

    // Generate random filename
    $filename = Str::random(40) . '.' . $file->extension();

    // Store outside public directory
    $path = $file->storeAs('avatars', $filename, 'private');

    return $path;
}
```

---

## 3. CSRF Protection

### 3.1 CSRF Tokens
```html
<!-- Always include CSRF token in forms -->
<form method="POST" action="/conversations">
    @csrf
    <!-- form fields -->
</form>

<!-- For AJAX requests -->
<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
</script>
```

### 3.2 SameSite Cookies
```php
// config/session.php
'same_site' => 'strict',
```

---

## 4. Authorization

### 4.1 Policy-Based Authorization
```php
// app/Policies/ConversationPolicy.php
class ConversationPolicy
{
    public function update(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->owner_id;
    }

    public function delete(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->owner_id;
    }

    public function view(User $user, Conversation $conversation): bool
    {
        if ($conversation->privacy === 'public') {
            return true;
        }

        if ($conversation->privacy === 'private') {
            return $conversation->isMember($user) || $conversation->isOwner($user);
        }

        return true;
    }
}

// Controller usage
public function update(Request $request, Conversation $conversation)
{
    $this->authorize('update', $conversation);
    // Update logic
}
```

### 4.2 Role-Based Access Control (RBAC)
```php
// Spatie Permission is already installed
$user->assignRole('admin');
$user->givePermissionTo('manage-conversations');

// Middleware
Route::middleware(['role:admin'])->group(function () {
    // Admin routes
});

Route::middleware(['permission:manage-conversations'])->group(function () {
    // Protected routes
});
```

---

## 5. API Security

### 5.1 API Authentication
```php
// Sanctum token authentication
Route::middleware('auth:sanctum')->group(function () {
    // Protected API routes
});

// Token generation with expiration
$token = $user->createToken('api-token', ['*'], now()->addDays(30));
```

### 5.2 API Key Security
```php
// Rotate API keys regularly
public function rotateApiKey(User $user)
{
    $oldKey = $user->apiKeys()->where('is_active', true)->first();
    $oldKey->update(['is_active' => false]);

    return $user->apiKeys()->create([
        'key' => 'jm_' . Str::random(40),
        'expires_at' => now()->addMonths(6),
    ]);
}

// Validate API key
public function handle($request, Closure $next)
{
    $apiKey = $request->header('X-API-Key');

    $key = ApiKey::where('key', $apiKey)
        ->where('is_active', true)
        ->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })
        ->first();

    if (!$key) {
        return response()->json(['error' => 'Invalid API key'], 401);
    }

    $key->update(['last_used_at' => now()]);

    return $next($request);
}
```

### 5.3 CORS Configuration
```php
// config/cors.php
'allowed_origins' => [
    'https://joinme.app',
    'https://www.joinme.app',
],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['Content-Type', 'Authorization'],
'exposed_headers' => [],
'max_age' => 3600,
'supports_credentials' => true,
```

---

## 6. Data Encryption

### 6.1 Encrypted Model Attributes
```php
use Illuminate\Database\Eloquent\Casts\Encrypted;

class User extends Model
{
    protected $casts = [
        'social_security_number' => Encrypted::class,
        'credit_card' => Encrypted::class,
    ];
}
```

### 6.2 Database Encryption at Rest
- Enable MySQL encryption at rest
- Use encrypted backups
- Encrypt sensitive columns

### 6.3 HTTPS Enforcement
```php
// app/Providers/AppServiceProvider.php
public function boot()
{
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}

// Redirect HTTP to HTTPS
public function handle($request, Closure $next)
{
    if (!$request->secure() && app()->environment('production')) {
        return redirect()->secure($request->getRequestUri());
    }

    return $next($request);
}
```

---

## 7. Security Headers

### 7.1 Implement Security Headers
```php
// app/Http/Middleware/SecurityHeaders.php
class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; " .
            "style-src 'self' 'unsafe-inline'; " .
            "img-src 'self' data: https:; " .
            "font-src 'self' data:; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none'"
        );

        return $response;
    }
}
```

---

## 8. Logging & Monitoring

### 8.1 Security Event Logging
```php
// Log security events
Log::channel('security')->warning('Failed login attempt', [
    'email' => $request->email,
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent(),
]);

Log::channel('security')->info('Suspicious activity detected', [
    'user_id' => $user->id,
    'action' => 'multiple_conversations_created',
    'count' => $count,
]);
```

### 8.2 Intrusion Detection
```php
// Monitor for suspicious patterns
- Multiple failed login attempts
- Rapid API requests
- Unusual data access patterns
- SQL injection attempts
- XSS attempts
```

---

## 9. Dependency Security

### 9.1 Regular Updates
```bash
# Check for security updates
composer outdated

# Update dependencies
composer update

# Audit packages
composer audit
```

### 9.2 Dependency Scanning
```bash
# Use Dependabot (GitHub)
# Use Snyk
# Use OWASP Dependency-Check
```

---

## 10. Environment Security

### 10.1 Environment Variables
```bash
# Never commit .env file
echo ".env" >> .gitignore

# Use strong APP_KEY
php artisan key:generate

# Secure sensitive values
DB_PASSWORD=strong_random_password
API_SECRET=random_secret_key
```

### 10.2 Debug Mode
```php
// .env
APP_DEBUG=false // Never true in production
APP_ENV=production
```

### 10.3 Error Handling
```php
// Don't expose stack traces in production
public function render($request, Throwable $exception)
{
    if (app()->environment('production')) {
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }

        return response()->view('errors.500', [], 500);
    }

    return parent::render($request, $exception);
}
```

---

## 11. Backup & Recovery

### 11.1 Regular Backups
```bash
# Database backups
php artisan backup:run

# Schedule backups
# app/Console/Kernel.php
$schedule->command('backup:run')->daily()->at('02:00');
```

### 11.2 Backup Encryption
```php
// config/backup.php
'destination' => [
    'disks' => ['s3'],
],
'backup' => [
    'password' => env('BACKUP_PASSWORD'),
],
```

---

## 12. Content Moderation

### 12.1 Profanity Filter
```bash
composer require snipe/banbuilder
```

### 12.2 Spam Detection
```php
// Detect spam patterns
- Multiple similar posts in short time
- Links to suspicious domains
- Repeated phrases
- Unusual activity patterns
```

### 12.3 User Reporting
- Allow users to report content
- Review reported content
- Take action (warn, suspend, ban)

---

## 13. Privacy & GDPR Compliance

### 13.1 Data Export
```php
// Allow users to export their data
public function export(User $user)
{
    return response()->json([
        'profile' => $user->toArray(),
        'conversations' => $user->conversations,
        'feedback' => $user->feedbackGiven,
        'messages' => $user->messages,
    ]);
}
```

### 13.2 Data Deletion
```php
// Allow users to delete their account
public function deleteAccount(User $user)
{
    DB::transaction(function () use ($user) {
        // Anonymize user data instead of hard delete
        $user->update([
            'email' => 'deleted_' . $user->id . '@deleted.com',
            'name' => 'Deleted User',
            'bio' => null,
            'avatar' => null,
        ]);

        // Delete sensitive data
        $user->participations()->delete();
        $user->messages()->delete();

        // Soft delete user
        $user->delete();
    });
}
```

### 13.3 Consent Management
- Cookie consent banner
- Privacy policy
- Terms of service
- Data processing agreements

---

## 14. Penetration Testing

### 14.1 Regular Security Audits
- Conduct annual penetration tests
- Use automated vulnerability scanners
- Code review by security experts
- Bug bounty program

### 14.2 Security Testing Tools
```bash
# OWASP ZAP
# Burp Suite
# Nikto
# SQLMap
# XSSer
```

---

## Security Checklist

### Authentication
- [ ] Strong password requirements
- [ ] Password hashing (bcrypt, rounds=12)
- [ ] Two-factor authentication
- [ ] Session security (secure, httponly, samesite)
- [ ] Rate limiting on login
- [ ] Account lockout after failed attempts

### Authorization
- [ ] Policy-based authorization
- [ ] Role-based access control
- [ ] Principle of least privilege
- [ ] Regular permission audits

### Input Validation
- [ ] Validate all user input
- [ ] Sanitize HTML input
- [ ] File upload validation
- [ ] SQL injection prevention
- [ ] XSS prevention

### API Security
- [ ] Authentication (Sanctum tokens)
- [ ] API rate limiting
- [ ] CORS configuration
- [ ] API key rotation
- [ ] Request validation

### Data Protection
- [ ] Encrypt sensitive data
- [ ] HTTPS enforcement
- [ ] Database encryption at rest
- [ ] Secure backup storage
- [ ] Data retention policies

### Security Headers
- [ ] X-Content-Type-Options: nosniff
- [ ] X-Frame-Options: DENY
- [ ] X-XSS-Protection
- [ ] Content-Security-Policy
- [ ] Referrer-Policy

### Monitoring
- [ ] Security event logging
- [ ] Failed login monitoring
- [ ] Suspicious activity detection
- [ ] Regular security audits
- [ ] Incident response plan

### Dependencies
- [ ] Regular dependency updates
- [ ] Security vulnerability scanning
- [ ] Remove unused dependencies
- [ ] Vendor security audits

### Environment
- [ ] Debug mode off in production
- [ ] Secure environment variables
- [ ] Error handling (no stack traces)
- [ ] Strong APP_KEY

### Privacy
- [ ] GDPR compliance
- [ ] Data export functionality
- [ ] Account deletion
- [ ] Privacy policy
- [ ] Cookie consent

---

## Incident Response Plan

### 1. Detection
- Monitor security logs
- Automated alerts
- User reports

### 2. Analysis
- Determine severity
- Identify affected systems
- Assess impact

### 3. Containment
- Isolate affected systems
- Block malicious IPs
- Revoke compromised credentials

### 4. Eradication
- Remove malware
- Patch vulnerabilities
- Update security rules

### 5. Recovery
- Restore from backups
- Verify system integrity
- Gradual service restoration

### 6. Post-Incident
- Document incident
- Update security measures
- Conduct lessons learned

---

## Conclusion

Security is an ongoing process, not a one-time task. Regularly review and update security measures, conduct audits, and stay informed about emerging threats. Prioritize user privacy and data protection at all times.
