<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Settings;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
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
        $settings = Settings::all();
        return view('pages.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Settings
     */
    public function create()
    {
        $plugins = DB::select(DB::raw("SELECT plugin FROM uninstall_tracking GROUP BY plugin"));
        return view('pages.settings.create',compact('plugins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Settings
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'plugins'   => 'required|unique:settings,plugin_name',
            'slag'      => 'required',
        ]);

        Settings::create([
            'plugin_name'   => $request->input('plugins'),
            'plugin_slag'   => $request->input('slag')
        ]);

        return redirect()->route('settings.index')
            ->with('success','Settings add successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Settings
     */
    public function edit($id)
    {
        $settings   = Settings::find($id);
        $plugins    = DB::select(DB::raw("SELECT plugin FROM uninstall_tracking GROUP BY plugin"));
        return view('pages.settings.edit',compact('settings','plugins'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Settings
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'plugins'   => 'required|unique:settings,plugin_name,'.$id,
            'slag'      => 'required',
        ]);

        $settings = Settings::find($id);
        $settings->plugin_name = $request->input('plugins');
        $settings->plugin_slag = $request->input('slag');
        $settings->save();

        return redirect()->route('settings.index')
            ->with('success','Settings updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Settings
     */
    public function destroy($id)
    {
        DB::table("settings")->where('id',$id)->delete();
        return redirect()->route('settings.index')
            ->with('success','Settings deleted successfully');
    }
}
