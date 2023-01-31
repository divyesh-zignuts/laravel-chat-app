<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $table = 'user_profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conversation_id',
        'firstname',
        'lastname',
        'phone',
        'email',
        'lead_quality',
        'lead_rating',
        'userid',
        'created_by',
        'updated_by',
        'status',
        'address',
    ];
}
