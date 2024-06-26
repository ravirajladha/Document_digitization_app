<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
class LogController extends Controller
{
    public function actionLogsIndex()
    {
        // Retrieve paginated results, 10 per page as an example
        $logs = DB::table('log_changes')
        ->leftJoin('users', 'log_changes.user_id', '=', 'users.id') // Perform a left join with the users table
        ->select('log_changes.*', 'users.name as user_name')
        ->orderBy('log_changes.created_at', 'desc') 
        ->orderBy('log_changes.id', 'desc')
        ->paginate(10);

        // Pass the paginated logs to the view
        return view('pages.logs.action-logs', compact('logs'));
        // return view('pages.logs.action-logs', compact('logs'))->with('logs', $logs->appends(request()->except('page'))->links('vendor.pagination.custom'));
    }
    public function httpRequestLogs()
    {
        // Retrieve paginated results, 10 per page as an example
        $logs = DB::table('http_request_logs')
        ->leftJoin('users', 'http_request_logs.user_id', '=', 'users.id') // Perform a left join with the users table
        ->select('http_request_logs.*', 'users.name as user_name')
        ->orderBy('http_request_logs.created_at', 'desc') 
        ->orderBy('http_request_logs.id', 'desc')// Select all columns from http_request_logs and the user's name
        ->paginate(10);

        // Pass the paginated logs to the view
        return view('pages.logs.http-request-logs', compact('logs'));
    }
}
