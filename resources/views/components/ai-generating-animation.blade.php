<div class="relative overflow-hidden bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-8 flex flex-col items-center justify-center" style="min-height: 500px;"
    x-data="{
        animation: null,
        initAnimation() {
            if (typeof window.lottie !== 'undefined') {
                const container = this.$refs.lottieContainer;
                if (container && !this.animation) {
                    container.innerHTML = '';
                    this.animation = window.lottie.loadAnimation({
                        container: container,
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        path: '/animations/robot-ai.json',
                        rendererSettings: {
                            preserveAspectRatio: 'xMidYMid meet'
                        }
                    });

                    // Force resize the SVG to fit container
                    this.animation.addEventListener('DOMLoaded', () => {
                        const svg = container.querySelector('svg');
                        if (svg) {
                            svg.setAttribute('width', '100%');
                            svg.setAttribute('height', '100%');
                        }
                    });
                }
            }
        }
    }"
    x-init="$nextTick(() => initAnimation())">

    <!-- Heading -->
    <h3 class="text-2xl font-bold text-center bg-gradient-to-r from-primary-600 to-primary-400 dark:from-primary-400 dark:to-primary-200 bg-clip-text text-transparent mb-8">
        Creating your study guide...
    </h3>

    <!-- Lottie Animation - Small Size, Centered -->
    <div class="w-32 h-32 overflow-hidden flex items-center justify-center" style="position: relative;">
        <div x-ref="lottieContainer" class="w-full h-full" wire:ignore style="position: relative;"></div>
    </div>
</div>

