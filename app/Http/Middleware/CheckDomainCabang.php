<?php

namespace App\Http\Middleware;

use App\Models\Cabang;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDomainCabang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya cek jika user sudah login
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip validasi domain untuk super admin
            if ($user->hasRole('super admin')) {
                return $next($request);
            }
            
            // Jika user memiliki kode_cabang
            if (!empty($user->kode_cabang)) {
                // Ambil domain dari request
                $currentDomain = $request->getHost();
                
                // Cari cabang berdasarkan kode_cabang
                $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
                
                if ($cabang && !empty($cabang->domain)) {
                    // Bandingkan domain cabang dengan domain yang sedang digunakan
                    if ($cabang->domain !== $currentDomain) {
                        // Domain tidak cocok, logout user
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        
                        return redirect()->route('login')
                            ->with('error', 'Anda tidak memiliki akses untuk domain ini. Silakan login melalui domain yang sesuai dengan cabang Anda.');
                    }
                }
            }
        }

        return $next($request);
    }
}
