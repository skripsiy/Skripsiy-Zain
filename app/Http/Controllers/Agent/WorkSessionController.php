<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentWorkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkSessionController extends Controller
{
    /**
     * Get current work session status
     */
    public function status()
    {
        $today = Carbon::today();
        $session = AgentWorkSession::where('user_id', Auth::id())
            ->where('work_date', $today)
            ->first();

        if (!$session) {
            return response()->json([
                'status' => 'offline',
                'total_online_seconds' => 0,
                'total_aux_seconds' => 0,
                'aux_remaining_seconds' => 3600,
                'current_session_start' => null
            ]);
        }

        // Calculate real-time online time if currently online
        $totalOnline = $session->total_online_seconds;
        if ($session->status === 'online' && $session->current_session_start) {
            $currentOnline = Carbon::parse($session->current_session_start)->diffInSeconds(Carbon::now());
            $totalOnline += $currentOnline;
        }

        return response()->json([
            'status' => $session->status,
            'total_online_seconds' => $totalOnline,
            'total_aux_seconds' => $session->total_aux_seconds,
            'aux_remaining_seconds' => $session->aux_remaining_seconds,
            'current_session_start' => $session->current_session_start,
            'shift_start' => $session->shift_start
        ]);
    }

    /**
     * Toggle online status (start shift or go online after break)
     */
    public function toggleOnline()
    {
        try {
            $today = Carbon::today();
            $now = Carbon::now();
            
            $session = AgentWorkSession::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'work_date' => $today
                ],
                [
                    'status' => 'offline',
                    'total_online_seconds' => 0,
                    'total_aux_seconds' => 0,
                    'aux_remaining_seconds' => 3600
                ]
            );

            if ($session->status === 'offline') {
                // Start shift or return from break
                if (!$session->shift_start) {
                    $session->shift_start = $now;
                }
                
                $session->status = 'online';
                $session->current_session_start = $now;
                $session->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Anda sekarang online. Selamat bekerja!',
                    'status' => 'online'
                ]);
            } 
            
            if ($session->status === 'online') {
                // Stop online, save accumulated time
                if ($session->current_session_start) {
                    $onlineSeconds = Carbon::parse($session->current_session_start)->diffInSeconds($now);
                    $session->total_online_seconds += $onlineSeconds;
                }
                
                $session->status = 'offline';
                $session->current_session_start = null;
                $session->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Anda sekarang offline',
                    'status' => 'offline'
                ]);
            }

            if ($session->status === 'aux') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sedang istirahat. Selesaikan istirahat terlebih dahulu.'
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Toggle online error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start AUX/Break time
     */
    public function startAux()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        
        $session = AgentWorkSession::where('user_id', Auth::id())
            ->where('work_date', $today)
            ->first();

        if (!$session || $session->status === 'offline') {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum online. Silakan online terlebih dahulu.'
            ], 400);
        }

        if ($session->status === 'aux') {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah dalam mode istirahat'
            ], 400);
        }

        if ($session->aux_remaining_seconds <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu istirahat Anda sudah habis'
            ], 400);
        }

        // Save accumulated online time
        if ($session->current_session_start) {
            $onlineSeconds = Carbon::parse($session->current_session_start)->diffInSeconds($now);
            $session->total_online_seconds += $onlineSeconds;
        }

        $session->status = 'aux';
        $session->current_session_start = $now;
        $session->save();

        return response()->json([
            'success' => true,
            'message' => 'Waktu istirahat dimulai. Maksimal 1 jam.',
            'status' => 'aux',
            'aux_remaining_seconds' => $session->aux_remaining_seconds
        ]);
    }

    /**
     * End AUX/Break time
     */
    public function endAux()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        
        $session = AgentWorkSession::where('user_id', Auth::id())
            ->where('work_date', $today)
            ->first();

        if (!$session || $session->status !== 'aux') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak sedang istirahat'
            ], 400);
        }

        // Calculate break time used
        if ($session->current_session_start) {
            $auxSeconds = Carbon::parse($session->current_session_start)->diffInSeconds($now);
            $session->total_aux_seconds += $auxSeconds;
            $session->aux_remaining_seconds = max(0, $session->aux_remaining_seconds - $auxSeconds);
        }

        $session->status = 'online';
        $session->current_session_start = $now;
        $session->save();

        return response()->json([
            'success' => true,
            'message' => 'Istirahat selesai. Selamat bekerja kembali!',
            'status' => 'online',
            'aux_remaining_seconds' => $session->aux_remaining_seconds
        ]);
    }

    /**
     * End shift
     */
    public function endShift()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        
        $session = AgentWorkSession::where('user_id', Auth::id())
            ->where('work_date', $today)
            ->first();

        if (!$session || $session->status === 'offline') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada shift aktif'
            ], 400);
        }

        // Save accumulated time
        if ($session->current_session_start) {
            if ($session->status === 'online') {
                $onlineSeconds = Carbon::parse($session->current_session_start)->diffInSeconds($now);
                $session->total_online_seconds += $onlineSeconds;
            } elseif ($session->status === 'aux') {
                $auxSeconds = Carbon::parse($session->current_session_start)->diffInSeconds($now);
                $session->total_aux_seconds += $auxSeconds;
                $session->aux_remaining_seconds = max(0, $session->aux_remaining_seconds - $auxSeconds);
            }
        }

        $session->shift_end = $now;
        $session->status = 'offline';
        $session->current_session_start = null;
        $session->save();

        $hours = floor($session->total_online_seconds / 3600);
        $minutes = floor(($session->total_online_seconds % 3600) / 60);

        return response()->json([
            'success' => true,
            'message' => 'Shift selesai. Total online time: ' . sprintf('%02d:%02d', $hours, $minutes),
            'status' => 'offline',
            'total_online_seconds' => $session->total_online_seconds,
            'total_aux_seconds' => $session->total_aux_seconds
        ]);
    }
}
