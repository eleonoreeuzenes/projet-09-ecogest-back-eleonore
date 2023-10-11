<?php

namespace App\Services;

use App\Models\User;
use DateTime;

class UserService
{
    public static function searchByUsernameOrEmail(String $q)
    {
        // Participant lists with details
        $users = User::where('username','ILIKE','%'.$q.'%')
            ->orWhere('email','ILIKE','%'.$q.'%')
            ->get();
        
            return $users ;
    }
}