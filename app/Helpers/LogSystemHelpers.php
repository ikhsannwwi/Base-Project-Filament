<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\LogSystem;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class LogSystemHelpers
{
    /**
     * Check if the user has the specified permission for the given menu ID.
     */
    public static function createLog($module, $action, $data_id, $data): void
    {
        $log['ip_address'] = request()->ip();
        $log['user_id'] = auth()->user()->id;

        $agent = new Agent();
        $log['device'] = $agent->device();
        $log['browser_name'] = $agent->browser();
        $log['browser_version'] = $agent->version($log['browser_name']);

        $log['module'] = $module;
        $log['action'] = $action;
        $log['data_id'] = $data_id;
        $log['data'] = json_encode($data);
        $log['created_at'] = now();

        LogSystem::create($log);
    }
}
