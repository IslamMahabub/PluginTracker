<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Response;
use Illuminate\Validation\ValidationException;

class AutoResponseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $responses = Response::all();
        return view('pages.responses.index', compact('responses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reasons = DB::select(DB::raw("SELECT reason_id FROM uninstall_tracking GROUP BY reason_id"));
        return view('pages.responses.create',compact('reasons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'reason'    => 'required|unique:responses,reason',
            'subject'   => 'required',
            'message'   => 'required',
        ]);

        Response::create([
            'reason'    => $request->input('reason'),
            'subject'   => $request->input('subject'),
            'message'   => $request->input('message'),
            'status'    => $request->input('status')
        ]);

        return redirect()->route('responses.index')
            ->with('success','Reason wise Message add successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response   = Response::find($id);
        $reasons    = DB::select(DB::raw("SELECT reason_id FROM uninstall_tracking GROUP BY reason_id"));
        return view('pages.responses.edit',compact('response','reasons'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'reason'    => 'required|unique:responses,reason,'.$id,
            'subject'   => 'required',
            'message'   => 'required',
        ]);

        $response = Response::find($id);
        $response->reason  = $request->input('reason');
        $response->subject = $request->input('subject');
        $response->message = $request->input('message');
        $response->status  = $request->input('status');
        $response->save();

        return redirect()->route('responses.index')
            ->with('success','Reason wise Message updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("responses")->where('id',$id)->delete();
        return redirect()->route('responses.index')
            ->with('success','Reason wise Message deleted successfully');
    }
}
