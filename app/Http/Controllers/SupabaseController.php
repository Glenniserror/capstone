<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;

class SupabaseController extends Controller
{
    /**
         * @return \Illuminate\Http\JsonResponse
         */
        public function test()
        {
            try {
            // Instantiate the client. If the package isn't installed this will throw.
            $client = SupabaseService::client();

            // Return minimal info so this endpoint can be used to verify the connection.
            \response()->json([
                'ok' => true,
                'message' => 'Supabase client instantiated',
            ]);
        } catch (\Throwable $e) {
            \response()->json([
                'ok' => false,
                'message' => 'Failed to instantiate Supabase client',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
