<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'role_id', 'provider', 'provider_id',
    'company', 'shipping_address1', 'shipping_address2',
    'shipping_city', 'shopping_postalcode', 'shipping_country',
    'shipping_countrycode', 'shipping_state', 'rewards_status', 'new_user_discount', 'preferred_discount_id', 'active',
    'user_token_1', 'user_token_2', 'email_verified_at', 'opt_in'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->user_token_1)) {
                $user->user_token_1 = \Illuminate\Support\Str::random(60);
            }
            if (empty($user->user_token_2)) {
                $user->user_token_2 = \Illuminate\Support\Str::random(60);
            }

            if (env('custom_login_security')) {
                if ($user->password) {
                    $raw = $user->password;
                    if (str_starts_with($raw, 'custom_raw:')) {
                        $raw = substr($raw, 11);
                    }
                    $user->password = hash_hmac('ripemd256', $raw, $user->user_token_1);
                }
            }
        });

        static::updating(function ($user) {
            if (env('custom_login_security')) {
                if ($user->isDirty('password') && $user->password) {
                    $raw = $user->password;
                    if (str_starts_with($raw, 'custom_raw:')) {
                        $raw = substr($raw, 11);
                    }
                    if (empty($user->user_token_1)) {
                        $user->user_token_1 = \Illuminate\Support\Str::random(60);
                    }
                    $user->password = hash_hmac('ripemd256', $raw, $user->user_token_1);
                }
            }
        });
    }

    protected $attributes = [
        'role_id' => 1,
        'opt_in' => false,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => env('custom_login_security') ? 'hashed' : 'hashed', // Cast can be hashed but custom hasher manages it if enabled
            'role_id'           => UserRole::class,
            'opt_in'            => 'boolean',
        ];
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'order_user_id');
    }

    public function preferredDiscount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Discount::class, 'preferred_discount_id');
    }

    public function hasPurchasedProduct(int $productId): bool
    {
        return $this->orders()
            ->where('order_status', 7)
            ->whereHas('details.variant', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }

    public function isEcommerceAdmin(): bool
    {
        return $this->isAdmin();
    }

    /**
     * The plain-text sentinel stored in the password column for guest checkout users.
     * It is intentionally NOT hashed so it can be compared with a simple string equality
     * check. Laravel's Auth::attempt() will fail to verify this against any bcrypt/argon2
     * hash, so guest users cannot log in through the standard login form.
     */
    public const GUEST_PASSWORD = '[GUEST-USER]';

    /**
     * Returns true for guest-checkout users: a user record created during checkout
     * where the customer did not choose a password.
     *
     * Detection is based on the plain-text sentinel '[GUEST-USER]' stored in the password
     * column — NOT on empty($this->password), because an empty column is ambiguous.
     *
     * Social login users are NEVER guests: SocialAuthController always assigns a real
     * hashed random password AND sets the provider column, so this check never fires for them.
     *
     * Regular registered users are NEVER guests: they supply their own password at
     * registration, which is always stored as a bcrypt/argon2 hash.
     */
    public function isGuest(): bool
    {
        return $this->password === self::GUEST_PASSWORD;
    }


    public function isAdmin(): bool
    {
        return $this->role_id === UserRole::Admin;
    }

    public function isWholesale(): bool
    {
        return $this->role_id === UserRole::Wholesale;
    }

    public function isTicketManager(): bool
    {
        return $this->role_id === UserRole::TicketManager;
    }

    public function isOrderProcessor(): bool
    {
        return $this->role_id === UserRole::OrderProcessor;
    }

    public function isStaff(): bool
    {
        return in_array($this->role_id, [
            UserRole::Admin,
            UserRole::OrderProcessor,
            UserRole::TicketManager
        ]);
    }

    /**
     * Prevent password reset emails from being sent
     * to social-provider accounts.
     */
    public function sendPasswordResetNotification($token): void
    {
        if (!empty($this->provider)) {
            return;
        }

        $tpl = null;
        if (!app()->runningUnitTests() || app()->bound('dynamic_email_test_active')) {
            $tpl = \App\Services\EmailTemplateService::getActiveTemplate('password_reset');
        }

        if ($tpl) {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $this->email
            ], false));

            $vars = [
                'customer_name' => $this->name,
                'reset_url' => $resetUrl,
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];

            \App\Services\EmailTemplateService::sendEmail('password_reset', $this->email, $this->name, $vars);
            return;
        }

        $this->notify(new ResetPassword($token));
    }

    /**
     * Override default email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $tpl = null;
        if (!app()->runningUnitTests() || app()->bound('dynamic_email_test_active')) {
            $tpl = \App\Services\EmailTemplateService::getActiveTemplate('account_activation');
        }

        if ($tpl) {
            $activationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $this->getKey(),
                    'hash' => sha1($this->getEmailForVerification()),
                ]
            );

            $vars = [
                'customer_name' => $this->name,
                'customer_email' => $this->email,
                'activation_url' => $activationUrl,
                'app_name' => config('app.name'),
                'year' => date('Y'),
            ];

            \App\Services\EmailTemplateService::sendEmail('account_activation', $this->email, $this->name, $vars);
            return;
        }

        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }
}