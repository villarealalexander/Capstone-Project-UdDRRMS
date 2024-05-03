<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public static function log($actionType, $details)
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role,
            'action_type' => $actionType,
            'details' => $details,
        ]);
    }
}