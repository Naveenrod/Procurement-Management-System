<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error - ProcureMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full mx-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/dashboard" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <span class="text-xl font-bold text-gray-800">ProcureMS</span>
            </a>
        </div>

        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-lg p-10 text-center">
            <!-- Illustration -->
            <div class="mb-6">
                <svg class="mx-auto w-32 h-32" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="64" cy="64" r="60" fill="#E0E7FF"/>
                    <!-- Gear 1 -->
                    <circle cx="52" cy="52" r="16" stroke="#6366F1" stroke-width="3" fill="none"/>
                    <circle cx="52" cy="52" r="6" fill="#6366F1"/>
                    <line x1="52" y1="32" x2="52" y2="36" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <line x1="52" y1="68" x2="52" y2="72" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <line x1="32" y1="52" x2="36" y2="52" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <line x1="68" y1="52" x2="72" y2="52" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <!-- Gear 2 -->
                    <circle cx="80" cy="72" r="12" stroke="#6366F1" stroke-width="3" fill="none"/>
                    <circle cx="80" cy="72" r="4" fill="#6366F1"/>
                    <line x1="80" y1="56" x2="80" y2="60" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <line x1="80" y1="84" x2="80" y2="88" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <!-- Lightning bolt (error) -->
                    <path d="M86 28L78 44H88L80 60" stroke="#EF4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-8xl font-bold text-indigo-600 mb-2">500</h1>

            <!-- Error Title -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Server Error</h2>

            <!-- Description -->
            <p class="text-gray-500 mb-8 leading-relaxed">
                Something went wrong on our end. Our team has been notified
                and is working to fix the issue. Please try again in a few moments.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="/dashboard"
                   class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg px-6 py-3 transition-colors duration-200 w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                    </svg>
                    Return to Dashboard
                </a>
                <button onclick="history.back()"
                        class="inline-flex items-center justify-center border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium rounded-lg px-6 py-3 transition-colors duration-200 w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Go Back
                </button>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-sm mt-6">
            &copy; {{ date('Y') }} ProcureMS. All rights reserved.
        </p>
    </div>
</body>
</html>
