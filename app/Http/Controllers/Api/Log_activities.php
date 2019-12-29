<?php

namespace Travelestt\Http\Controllers\Api;

use Illuminate\Http\Request;
use Travelestt\Models\Log_activity;
use Travelestt\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class Log_activities extends Controller
{
    public function __construct(Log_activity $log, APILogin $api_login)
    {
        $this->logs = $log;
        $this->check_user = $api_login;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $logs = $this->logs->formatLog()->get();

        if (count($logs) == 0) {
            return response()->json(['message' => __('messages.logs_not_found'), 'status' => 'error'], 404); 
        }
        
        return response()->json(['message' => $logs, 'status' => 'success'], 200);     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $log = $this->logs->formatLog()->find($id);

        if (count($log) == 0) {
            return response()->json(['message' => __('messages.log_not_found') , 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $log, 'status' => 'success'], 200);     
    }

    public function getLogByUser(Request $request, $id)
    {
        $logs = $this->logs->formatLog()->byUser($id)->get();

        if (count($logs) == 0) {
            return response()->json(['message' => __('messages.logs_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $logs, 'status' => 'success'], 200); 
    }

    public function getLogBySpecificDate(Request $request)
    {
        if (!isset($request->date)) {
            return response()->json(['message' => __('messages.no_date_submitted'), 'status' => 'success'], 200); 
        }
        $date = $request->date;
        $logs = $this->logs->formatLog()->bySpecificDate($date)->get();

        if (count($logs) == 0) {
            return response()->json(['message' => __('messages.logs_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $logs, 'status' => 'success'], 200); 
    }

    public function getLogByRangeOfDate(Request $request)
    {
        
        if (!isset($request->from) && !isset($request->to)) {
            return response()->json(['message' => __('messages.no_dates_submitted'), 'status' => 'success'], 200); 
        }
        $from = $request->from; 
        $to = $request->to;
        $logs = $this->logs->byRangeOfDate($from, $to)->get();

        if (count($logs) == 0) {
            return response()->json(['message' => __('messages.logs_not_found'), 'status' => 'error'], 404); 
        }

        return response()->json(['message' => $logs, 'status' => 'success'], 200); 
    }
}
