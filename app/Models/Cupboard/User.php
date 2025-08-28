<?php

namespace App\Models\Cupboard;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasUuidTrait;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable, HasUuidTrait;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'is_landlord',
        'language',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected $with = ['cart'];

    public function getFullName()
    {
        $firstName = $this->first_name;
        $lastName = $this->last_name;

        return $firstName.' '.$lastName;
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = config('app.frontend_url').'/reset-password?token='.$token.'&email='.$this->email;

        $this->notify(new class($url) extends ResetPassword {
            private $url;
            public function __construct($url) { $this->url = $url; }
            public function via($notifiable) { return ['mail']; }
            public function toMail($notifiable)
            {
                return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Reset Password Notification')
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Reset Password', $this->url)
                    ->line('If you did not request a password reset, no further action is required.');
            }
        });
    }
}
