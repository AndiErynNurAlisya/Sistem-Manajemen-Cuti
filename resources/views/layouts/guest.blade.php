<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #f6f4f0 0%, #b5b89b 50%, #566534 100%);
            }
            
            .glass-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(181, 184, 155, 0.3);
            }
            
            .input-field {
                background: rgba(86, 101, 52, 0.05);
                border: 2px solid #b5b89b;
                transition: all 0.3s ease;
            }
            
            .input-field:focus {
                background: rgba(255, 255, 255, 0.9);
                border-color: #566534;
                outline: none;
                ring: 0;
            }
            
            .login-btn {
                background: linear-gradient(135deg, #566534 0%, #334124 100%);
                transition: all 0.3s ease;
            }
            
            .login-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(51, 65, 36, 0.3);
            }

            .logo-overlap {
                position: relative;
                margin-bottom: -60px;
                z-index: 10;
            }

            .avatar-circle {
                background: #f6f4f0;
                width: 120px;
                height: 120px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 30px rgba(51, 65, 36, 0.4);
                border: #f6f4f0;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex items-center justify-center gradient-bg p-4">
            <div class="w-full max-w-md">
                <!-- Logo Overlap (Bertumpuk) -->
                <div class="flex justify-center logo-overlap">
                    <div class="avatar-circle">
                        <a href="/">
                            <x-application-logo class="w-16 h-16 fill-current text-white" />
                        </a>
                    </div>
                </div>

                <!-- Login Card -->
                <div class="glass-card rounded-3xl shadow-2xl px-8 pt-20 pb-8">
                    {{ $slot }}
                </div>

                <!-- Footer Text -->
                <div class="text-center mt-6">
                    <p class="text-sm" style="color: #334124; opacity: 0.8;">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>