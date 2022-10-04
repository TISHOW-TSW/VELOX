<?php

namespace App\Helpers;
//use Illuminate\Http\Request;

use App\Models\Extrato;
use Request;






class LogActivity
{
    public static function addToLog($subject)
    {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
        Extrato::create($log);
    }


    public static function logActivityLists()
    {
        return Extrato::latest()->get();
    }
}
