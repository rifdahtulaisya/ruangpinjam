<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
     public function index(Request $request)
    {
        $filter = $request->filter;

        $query = LogAktivitas::with('user')->latest();

        if ($filter) {
            $query->where(function($q) use ($filter) {
                $q->where('role', $filter)
                  ->orWhere('modul', $filter);
            });
        }

        $logs = $query->paginate(10)->withQueryString();

        return view('admin.log.index', compact('logs', 'filter'));
    }


}
