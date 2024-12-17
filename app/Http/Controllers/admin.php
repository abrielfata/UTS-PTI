<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class Admin extends Controller
{
    public function index(Request $request)
    {
        $query = Team::query();
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('jabatan', 'like', '%' . $search . '%');
        }

        $teams = $query->get(); 
        return view('team.index', compact('teams'));
    }

    public function create()
    {
        return view('team.create');
    }

    public function control(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:teams,email,',
            'phone' => 'required',
            'jabatan' => 'required',
        ]);

        Team::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('team.index')->with('success', 'Team created successfully.');
    }


    public function edit($id)
    {
        $team = Team::find($id);
        if (!$team) {
            return redirect()->route('team.index')->with('error', 'Team not found.');
        }
        return view('team.edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:teams,email,' . $team->id, 
            'phone' => 'required',
            'jabatan' => 'required',
        ]);

        $team->name = $request->name;
        $team->email = $request->email;
        $team->phone = $request->phone;
        $team->jabatan = $request->jabatan;
        $team->save();
        
        return redirect()->route('team.index')->with('success', 'Team updated successfully.');
    }

    public function destroy($id)
    {
        $team = Team::find($id);
        if (!$team) {
            return redirect()->route('team.index')->with('error', 'Team not found.');
        }
        $team->delete();
        return redirect()->route('team.index')->with('success', 'Team deleted successfully.');
    }

    public function report()
    {
        $teams = Team::all();
        $pdf = Pdf::loadView('team.report', compact('teams'));
        return $pdf->download('team-report.pdf');
    }

}
