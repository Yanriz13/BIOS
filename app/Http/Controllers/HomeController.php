<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\DailyRoutineChecklist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data laporan
        if ($user->role === 'super_admin') {

            $data = Laporan::latest()->get();

            $locations = DailyRoutineChecklist::query()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->latest()
                ->get();

        } else {

            $data = Laporan::where(
                'divisi',
                $user->divisi
            )->latest()->get();

            $locations = DailyRoutineChecklist::whereHas(
                'routine.user',
                function ($q) use ($user) {
                    $q->where('divisi', $user->divisi);
                }
            )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->get();
        }

        return view('dashboard.main', [
            'data' => $data,
            'locations' => $locations,
        ]);
    }
}