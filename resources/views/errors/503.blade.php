<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - Pioneer Missions Africa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #1e3a8a, #3b82f6, #60a5fa, #1e40af);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .slide-up {
            animation: slide-up 0.8s ease-out forwards;
        }

        .delay-1 { animation-delay: 0.2s; opacity: 0; }
        .delay-2 { animation-delay: 0.4s; opacity: 0; }
        .delay-3 { animation-delay: 0.6s; opacity: 0; }
        .delay-4 { animation-delay: 0.8s; opacity: 0; }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full">
        <div class="text-center">
            <!-- Logo -->
            <div class="mb-12 float-animation">
                <img
                    src="{{ url('images/PMALogoWhiteText.png') }}"
                    alt="Pioneer Missions Africa"
                    class="h-24 md:h-32 mx-auto drop-shadow-2xl"
                >
            </div>

            <!-- Main Content -->
            <div class="space-y-6">
                <!-- Coming Soon Badge -->
                <div class="slide-up delay-1">
                    <span class="inline-block px-6 py-2 bg-white/20 backdrop-blur-lg rounded-full text-white font-semibold text-sm tracking-wider border border-white/30">
                        COMING SOON
                    </span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 slide-up delay-2">
                    Something Amazing<br>
                    <span class="bg-gradient-to-r from-yellow-200 to-yellow-400 bg-clip-text text-transparent">
                        Is On Its Way
                    </span>
                </h1>

                <!-- Description -->
                <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto mb-8 slide-up delay-3">
                    We're building an incredible new experience for Pioneer Missions Africa.
                    Our mission continues, and we can't wait to share what's next with you.
                </p>

                <!-- Progress Indicator -->
                <div class="slide-up delay-4">
                    <div class="max-w-md mx-auto mb-8">
                        <div class="flex items-center justify-center space-x-2 mb-4">
                            <div class="w-3 h-3 bg-white rounded-full pulse-glow"></div>
                            <div class="w-3 h-3 bg-white/70 rounded-full pulse-glow" style="animation-delay: 0.3s;"></div>
                            <div class="w-3 h-3 bg-white/40 rounded-full pulse-glow" style="animation-delay: 0.6s;"></div>
                        </div>
                        <p class="text-white/80 text-sm">
                            We're working hard behind the scenes
                        </p>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="slide-up delay-4">
                    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 max-w-md mx-auto">
                        <h3 class="text-white font-semibold text-xl mb-4">Stay Connected</h3>
                        <p class="text-white/80 text-sm mb-6">
                            Follow our journey as we continue to serve and spread the mission.
                        </p>

                        <!-- Contact Info -->
                        <div class="space-y-3 text-white/90 text-sm">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span>info@pioneermissionsafrica.co.za</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-12 text-white/60 text-sm slide-up delay-4">
                    <p>&copy; {{ date('Y') }} Pioneer Missions Africa. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
