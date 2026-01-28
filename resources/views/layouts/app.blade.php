<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#003d9e', // Brand Blue
                        secondary: '#64748B', // Slate 500
                        bgLight: '#F3F4F6', // Gray 100
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F9FB; /* Light background from image */
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1; 
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8; 
        }
        .sidebar-link {
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #F3F4F6;
            color: #111827;
        }
        
        /* Flatpickr Custom Design */
        .flatpickr-calendar {
            background-color: #ffffff !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem !important;
            width: auto !important;
        }
        .flatpickr-calendar.arrowTop:before, .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #ffffff !important;
        }
        .flatpickr-calendar.arrowBottom:before, .flatpickr-calendar.arrowBottom:after {
            border-top-color: #ffffff !important;
        }
        .flatpickr-months {
            background-color: transparent !important;
            margin-bottom: 0.5rem !important;
        }
        .flatpickr-months .flatpickr-month {
            background: transparent !important;
            color: #1e293b !important;
            fill: #1e293b !important;
        }
        .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
            color: #64748b !important;
            fill: #64748b !important;
        }
        .flatpickr-months .flatpickr-prev-month:hover svg, .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #003d9e !important;
        }
        .flatpickr-current-month {
            color: #1e293b !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 600 !important;
        }
        .flatpickr-weekdays {
            background: transparent !important;
            margin-bottom: 0.25rem !important;
        }
        .flatpickr-weekday {
            background: transparent !important;
            color: #64748b !important;
            font-weight: 600 !important;
            font-size: 0.75rem !important;
        }
        .flatpickr-day {
            color: #334155 !important;
            border-radius: 0.5rem !important;
            font-weight: 500 !important;
        }
        .flatpickr-day:hover, .flatpickr-day.prevMonthDay:hover, .flatpickr-day.nextMonthDay:hover, .flatpickr-day:focus {
            background-color: #f1f5f9 !important;
            border-color: #f1f5f9 !important;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #003d9e !important;
            color: #fff !important;
            border-color: #003d9e !important;
        }
        .flatpickr-day.inRange {
            box-shadow: -5px 0 0 #dbeafe, 5px 0 0 #dbeafe !important;
            background-color: #dbeafe !important;
            border-color: #dbeafe !important;
            color: #003d9e !important;
        }
        .flatpickr-day.today {
            border-color: #003d9e !important;
            background-color: #eff6ff !important; /* Blue 50 */
            color: #003d9e !important;
            font-weight: 700 !important;
        }
        .flatpickr-day.today:hover {
            border-color: #003d9e !important;
            background-color: #dbeafe !important; /* Blue 100 */
            color: #003d9e !important;
        }
    </style>
    @include('layouts.styles')
    @yield('style')
</head>
<body class="text-slate-600 antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <!-- Top Header -->
            <header class="h-16 bg-white/50 backdrop-blur-md border-b border-transparent shadow-md flex items-center justify-between px-6 z-10 sticky top-0">
                <!-- Left: Title -->
                 <div class="flex items-center gap-4">
                     <button class="md:hidden text-slate-500 hover:text-slate-700">
                        <i class="fas fa-bars text-lg"></i>
                     </button>
                    <h1 class="text-xl font-bold text-slate-800">Dashboard</h1>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-4">
                    <!-- Avatars -->
                    <div class="hidden sm:flex items-center -space-x-2">
                         <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=5" alt="User 1">
                         <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=9" alt="User 2">
                         <button class="w-8 h-8 rounded-full border-2 border-white bg-white text-slate-400 flex items-center justify-center text-xs font-medium hover:bg-slate-50">
                             +2
                         </button>
                    </div>
                    
                    <div class="w-px h-6 bg-slate-200 hidden sm:block"></div>

                    <!-- Notification -->
                    <button class="relative w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                        <i class="far fa-bell"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <!-- Search -->
                    <div class="relative hidden lg:block">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" placeholder="Search anything" class="pl-9 pr-12 py-2 bg-white border border-slate-200 rounded-full text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 shadow-sm">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-slate-100 text-slate-500 text-xs px-1.5 py-0.5 rounded border border-slate-200">⌘K</span>
                    </div>

                    <!-- User Profile -->
                     <button class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 hover:ring-2 hover:ring-indigo-500/20 transition-all">
                        <img src="https://i.pravatar.cc/100?img=32" alt="Profile" class="w-full h-full object-cover">
                     </button>
                     <button class="text-slate-400 hover:text-slate-600">
                         <i class="fas fa-chevron-down text-xs"></i>
                     </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-[#F8F9FB] p-6">
                <!-- <div class="max-w-7xl mx-auto"> -->
                    @yield('content')
                <!-- </div> -->
            </main>
        </div>
    </div>
    
    <!-- Original Scripts might be needed if they contain logic, but be careful of conflicts -->
    @include('layouts.scripts')
</body>
</html>
