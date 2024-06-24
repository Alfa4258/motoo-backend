<?php

namespace App\Providers;

use App\Models\Pic;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    
        User::created(function ($user) {
            $this->savePIC($user);
        });
    
        User::updated(function ($user) {
            $this->savePIC($user);
        });
    }
    
    private function savePIC($user)
    {
        if ($user->role === 'teknisi' || $user->role === 'client') {
            // Cari apakah data PIC untuk user ini sudah ada berdasarkan user_id
            $pic = Pic::where('user_id', $user->id)->first();
    
            if (!$pic) {
                // Jika tidak ada, buat entri PIC baru
                $pic = new Pic();
                $pic->user_id = $user->id;  // Mengaitkan dengan user_id
            }
    
            // Update atau isi data PIC
            $pic->name = $user->name;
            $pic->contact = $user->phone;
            $pic->photo = $user->photo;
            $pic->save();
        }
    }
    

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
