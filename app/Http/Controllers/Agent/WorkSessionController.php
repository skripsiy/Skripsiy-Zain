<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentWorkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkSessionController extends Controller
{
    public function getStatus()
    {
        $activeSession = AgentWorkSession::where('user_id', Auth::id())
            ->whereNull('end_time')
            ->first();

        if ($activeSession) {
            $duration = Carbon::parse($activeSession->start_time)->diffInMinutes(Carbon::now());
            $hours = floor($duration / 60);
            $minutes = $duration % 60;

            return response()->json([
                'active' => true,
                'start_time' => $activeSession->start_time,
                'duration' => sprintf('%02d:%02d', $hours, $minutes),
                'duration_minutes' => $duration
            ]);
        }

        return response()->json(['active' => false]);
    }

    public function start()
    {
        // Check if there's already an active session
        $activeSession = AgentWorkSession::where('user_id', Auth::id())
            ->whereNull('end_time')
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki sesi aktif'
            ], 400);
        }

        $session = AgentWorkSession::create([
            'user_id' => Auth::id(),
            'start_time' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sesi kerja dimulai',
            'session' => $session
        ]);
    }

    public function end(Request $request)
    {
        $request->validate([
            'end_type' => 'required|in:break,finish'
        ]);

        $activeSession = AgentWorkSession::where('user_id', Auth::id())
            ->whereNull('end_time')
            ->first();

        if (!$activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sesi aktif'
            ], 400);
        }

        $endTime = Carbon::now();
        $duration = Carbon::parse($activeSession->start_time)->diffInMinutes($endTime);

        $activeSession->update([
            'end_time' => $endTime,
            'end_type' => $request->end_type,
            'duration_minutes' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->end_type === 'break' ? 'Sesi istirahat dimulai' : 'Sesi kerja selesai',
            'duration_minutes' => $duration
        ]);
    }
}
