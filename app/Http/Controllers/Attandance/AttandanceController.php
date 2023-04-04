<?php

namespace App\Http\Controllers\Attandance;

use App\Http\Controllers\Controller;
use App\Models\Attandance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttandanceController extends Controller
{
    public function checkIn()
    {
        $checkIn = Attandance::where('user_id', auth()->user()->id)->whereDate('check_in',date('Y-m-d'))->first();
        if (!$checkIn) {
            $attandance = new Attandance();
            $attandance->user_id = auth()->user()->id;
            $attandance->check_in = Carbon::now();
            $attandance->save();
        return response()->json(['status' => 200, 'message' => 'You were checked in']);
        }else{
            $checkIn->update([
                'check_out' => Carbon::now()
            ]);
        return response()->json(['status' => 200, 'message' => 'You were checked out']);
        }
    }
}
