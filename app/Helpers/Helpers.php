<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Helpers
{
    /**
     * Check if the user has the specified permission for the given menu ID.
     */
    public function asset_frontpage($url){
        return asset('frontpage/' . $url);
    }
}
