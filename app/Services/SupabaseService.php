<?php

namespace App\Services;

use Supabase\Client\Functions;

class SupabaseService
{
    /**
     * Create a Supabase Functions client instance.
     *
     * @return \Supabase\Client\Functions
     */
    public static function client(): Functions
    {
        $url = \config('supabase.url');
        $key = \config('supabase.key');

        // The vendor package exposes higher-level methods via the Functions class
        // which extends the base Supabase client and provides public helpers.
        return new Functions($url, $key);
    }
}
