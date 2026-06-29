<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'campaign',
        'area',
        'site',
        'username',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send a password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is team leader
     */
    public function isTeamLeader(): bool
    {
        return $this->role === 'team_leader';
    }

    /**
     * Check if user is agent
     */
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    /**
     * Ambil divisi dari kolom campaign (area, besfixed, saltik)
     */
    public function getDivision(): string
    {
        return strtolower($this->campaign ?? 'besfixed');
    }

    /**
     * Relationship ke work sessions
     */
    public function workSessions()
    {
        return $this->hasMany(AgentWorkSession::class);
    }

    /**
     * Tickets assigned to this user
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to_user_id');
    }

    /**
     * Tickets solved by this user
     */
    public function solvedTickets()
    {
        return $this->hasMany(Ticket::class, 'solved_by_user_id');
    }
}
