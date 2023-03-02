<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Facades\DB;


class MeetingController extends Controller
{

    public function addMeeting(Request $request)
    {
        $minutes=Meeting::select(DB::raw("SUM(meeting_time) as total_time"))
        ->where(['user_id'=>$request->user_id,'date'=>$request->date])->get();
        // echo $minutes[0]['total_time'];
        $currentUserMinute=9*60;
        if($minutes[0]['total_time'] >= $currentUserMinute){
            return redirect()->back()->with('error','On this date '.$request->date.' all schedules are busy select another date');

        }
        else if(($minutes[0]['total_time']+$request->time) > $currentUserMinute)
        {
            $currentMinute=$currentUserMinute-$minutes[0]['total_time'];
            return redirect()->back()->with('error1','On this date '.$request->date.' you have only ' .$currentMinute. 'Minutes');

        }
        else{
            $meeting=new Meeting();
            $meeting->name=$request->name;
            $meeting->user_id=$request->user_id;
            $meeting->location=$request->location;
            $meeting->latitude=$request->latitude;
            $meeting->longitude=$request->longitude;
            $meeting->city=$request->city;
            $meeting->ip=$request->ip;
            $meeting->meeting_time=$request->meeting_time;
            $meeting->distance_time=$request->dtime;
            $meeting->distance_kilometer=$request->dkm;
            $meeting->date=$request->date;
            $meeting->save();
    
            return redirect()->back()->with('status','Record Sucessfully save');
    
        }
        


        // 

    }


    public function getDateMeetings(Request $request)
    {
        $meetings=Meeting::where('date',$request->date)->get();
        return response()->json(['meeting'=>$meetings]);
    }





}
