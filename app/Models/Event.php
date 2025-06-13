<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attendee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class Event extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}
