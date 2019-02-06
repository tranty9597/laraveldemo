<?php

namespace App\Http\Controllers\Api\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LRedis;
use Laravel\Passport\HasApiTokens;

class RedisController extends Controller
{
    // use HasApiTokens;

    public function index()
    {
        $test = LRedis::get("test");
        return response()->json(["message" => $test], 200);
    }

    public function writemessage()
    {
        return view('writemessage');
    }

    public function sendMessage()
    {
        $redis = LRedis::connection();
        $redis->set("test", request('message'));
        return response()->json(["message" => request('message')], 200);
    }
}
