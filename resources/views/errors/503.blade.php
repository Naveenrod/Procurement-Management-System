<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Service Unavailable - ProcureMS</title>
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
                    <!-- Wrench -->
                    <path d="M46 82L70 58" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="76" cy="52" r="14" stroke="#6366F1" stroke-width="3" fill="none"/>
                    <path d="M86 42L96 32" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <path d="M96 32L96 42L86 42" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <!-- Screwdriver -->
                    <rect x="34" y="78" width="16" height="8" rx="2" transform="rotate(-45 34 78)" fill="#6366F1"/>
                    <line x1="42" y1="86" x2="50" y2="94" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                    <!-- Clock indicator -->
                    <circle cx="90" cy="82" r="10" stroke="#6366F1" stroke-width="2" fill="white"/>
                    <line x1="90" y1="82" x2="90" y2="76" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                    <line x1="90" y1="82" x2="94" y2="82" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-8xl font-bold text-indigo-600 mb-2">503</h1>

            <!-- Error Title -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Service Unavailable</h2>

            <!-- Description -->
            <p class="text-gray-500 mb-4 leading-relaxed">
                We're performing scheduled maintenance. Please check back shortly.
            </p>

            <!-- Maintenance Notice -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-center text-indigo-700">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">
                        Our team is working hard to get things back up and running.
                    </span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="/dashboard"
                   class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg px-6 py-3 transition-colors duration-200 w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Try Again
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
