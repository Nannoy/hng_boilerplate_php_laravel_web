<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Auth;

class NotificationPreferenceController extends Controller
{
    /**
     * Store the notification preferences for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function set(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'email_notifications' => 'required|boolean',
            'push_notifications' => 'required|boolean',
            'sms_notifications' => 'required|boolean',
        ]);

        $user = Auth::user();

        // Check if preferences already exist for the user
        $existingPreferences = NotificationPreference::where('user_id', $user->id)->first();

        if ($existingPreferences) {
            // Return a message if preferences are already set
            return response()->json([
                'message' => 'Notification preferences already set.',
                'preferences' => $existingPreferences
            ], 409); // 409 Conflict status code
        }

        // Create new preferences if none exist
        $preferences = NotificationPreference::create([
            'user_id' => $user->id,
            'email_notifications' => $validated['email_notifications'],
            'push_notifications' => $validated['push_notifications'],
            'sms_notifications' => $validated['sms_notifications'],
        ]);

        return response()->json([
            'message' => 'Notification preferences created successfully.',
            'preferences' => $preferences
        ], 201); // 201 Created status code
    }
}