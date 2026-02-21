<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found - ProcureMS</title>
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
                <svg class="mx-auto w-32 h-32 text-indigo-100" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="64" cy="64" r="60" fill="currentColor"/>
                    <path d="M44 50C44 47.79 45.79 46 48 46C50.21 46 52 47.79 52 50C52 52.21 50.21 54 48 54C45.79 54 44 52.21 44 50Z" fill="#6366F1"/>
                    <path d="M76 50C76 47.79 77.79 46 80 46C82.21 46 84 47.79 84 50C84 52.21 82.21 54 80 54C77.79 54 76 52.21 76 50Z" fill="#6366F1"/>
                    <path d="M44 82C44 82 52 74 64 74C76 74 84 82 84 82" stroke="#6366F1" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-8xl font-bold text-indigo-600 mb-2">404</h1>

            <!-- Error Title -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Page Not Found</h2>

            <!-- Description -->
            <p class="text-gray-500 mb-8 leading-relaxed">
                The page you are looking for doesn't exist or has been moved.
                Please check the URL or navigate back to the dashboard.
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
