@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    <style>
        #tab-content-main {
            box-shadow: none !important;
            background: none !important;
        }

        .greeting-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            margin-bottom: 2rem;
        }

        .greeting-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
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
            background: rgba(255, 255, 255, 0.08);
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
            opacity: 0.95;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .greeting-time i {
            font-size: 1.2rem;
        }

        .greeting-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .greeting-role {
            font-size: 1rem;
            opacity: 0.9;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .greeting-role i {
            font-size: 1rem;
        }

        .greeting-icon {
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 8rem;
            opacity: 0.15;
            z-index: 0;
        }

        @media (max-width: 768px) {
            .greeting-card {
                padding: 1.5rem;
            }

            .greeting-name {
                font-size: 1.5rem;
            }

            .greeting-icon {
                font-size: 5rem;
                right: 1rem;
            }
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
    <div class="col-xl-12">
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
