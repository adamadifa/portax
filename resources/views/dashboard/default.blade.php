@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }

        .greeting-card {
            background: linear-gradient(135deg, #003d9e 0%, #002a6b 100%);
            border-radius: 12px;
            padding: 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 61, 158, 0.3);
            margin-bottom: 2rem;
        }

        .greeting-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            z-index: 0;
        }

        .greeting-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            z-index: 0;
        }

        .greeting-content {
            position: relative;
            z-index: 1;
        }

        .greeting-time {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #e0e7ff;
        }

        .greeting-name {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            color: #ffffff;
        }

        .greeting-role {
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .greeting-icon {
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 9rem;
            opacity: 0.1;
            z-index: 0;
            color: white;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border-color: #003d9e;
        }

        .icon-box {
            background-color: #eff6ff;
            color: #003d9e;
        }
        
        .stat-card:hover .icon-box {
            background-color: #003d9e;
            color: #ffffff;
        }
    </style>
@section('navigasi')
    @include('dashboard.navigasi')
@endsection
@php
    $user = Auth::user();
    $hour = date('H');
    if ($hour < 11) {
        $greeting = 'Selamat Pagi';
        $icon = 'ti-sun';
    } elseif ($hour < 15) {
        $greeting = 'Selamat Siang';
        $icon = 'ti-sun-high';
    } elseif ($hour < 19) {
        $greeting = 'Selamat Sore';
        $icon = 'ti-sunset';
    } else {
        $greeting = 'Selamat Malam';
        $icon = 'ti-moon';
    }
@endphp
<div class="row">
    <div class="col-xl-12 mb-4">
        <div class="greeting-card">
            <div class="greeting-icon">
                <i class="ti {{ $icon }}"></i>
            </div>
            <div class="greeting-content">
                <div class="greeting-time">
                    <i class="ti {{ $icon }}"></i>
                    {{ $greeting }}
                </div>
                <div class="greeting-name">
                    {{ textCamelCase($user->name) }}! 👋
                </div>
                <div class="greeting-role">
                    <i class="ti ti-user-circle"></i>
                    <span>{{ textCamelCase($level_user ?? 'User') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
