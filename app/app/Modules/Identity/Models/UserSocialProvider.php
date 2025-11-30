<?php

namespace App\Modules\Identity\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialProvider extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'email',
        'avatar',
    ];
}
