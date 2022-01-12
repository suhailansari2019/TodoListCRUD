<?php

namespace App\Http\Controllers;

use App\Models\task;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function main(){
        return view('main');
    }
    
    public function index()
    {
        //
        $data['task'] = task::orderBy('id','desc')->paginate(8);
   
        return view('task.index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $taskID = $request->task_id;
        
        $task   =   task::updateOrCreate(['id' => $taskID],
                    ['subject' => $request->subject, 'current_date' => $request->current_date]);
    
        return response()->json($task);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        
        $task  = task::where('id',$id)->first();
 
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task = task::where('id',$id)->delete();
   
        return response()->json($task);
    }
}
