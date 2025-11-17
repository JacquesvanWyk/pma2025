import { gsap } from 'gsap';
import { TextPlugin } from 'gsap/TextPlugin';

// Hero section animations
document.addEventListener('DOMContentLoaded', function() {
    
    // Timeline for hero animations
    const heroTl = gsap.timeline();
    
    // Set initial states
    gsap.set('.hero-title', { opacity: 1 });
    gsap.set('.hero-subtitle', { opacity: 0, y: 30 });
    gsap.set('.hero-description', { opacity: 0, y: 20 });
    gsap.set('.hero-cta', { opacity: 0, scale: 0.8 });
    gsap.set('.hero-image', { opacity: 0, x: 100 });
    gsap.set('.hero-stats', { opacity: 0, y: 30 });
    gsap.set('.bible-animation', { opacity: 0, scale: 0.8, rotateY: -90 });
    gsap.set('.divine-light', { opacity: 0, scale: 0 });
    
    // Typing effect for title
    const titleElement = document.querySelector('.hero-title-text');
    if (titleElement) {
        const titleText = titleElement.textContent;
        titleElement.textContent = '';
        titleElement.style.opacity = '1';
        
        // Add typing cursor
        const cursor = document.createElement('span');
        cursor.className = 'typing-cursor';
        cursor.textContent = '|';
        titleElement.appendChild(cursor);
        
        // Typing animation
        heroTl.to(titleElement, {
            text: titleText,
            duration: 3,
            ease: 'none',
            onComplete: function() {
                // Remove cursor after typing
                gsap.to(cursor, { opacity: 0, duration: 0.5 });
                // Trigger Bible animation after "Gospel"
                triggerBibleAnimation();
            }
        });
    }
    
    // Continue with other animations after typing
    heroTl
        .to('.hero-subtitle', { 
            opacity: 1, 
            y: 0, 
            duration: 0.8,
            ease: 'power2.out'
        }, '-=0.5')
        .to('.hero-description', { 
            opacity: 1, 
            y: 0, 
            duration: 0.8,
            ease: 'power2.out'
        }, '-=0.4')
        .to('.hero-cta', { 
            opacity: 1, 
            scale: 1, 
            duration: 0.6,
            ease: 'back.out(1.7)'
        }, '-=0.3')
        .to('.hero-image', { 
            opacity: 1, 
            x: 0, 
            duration: 1,
            ease: 'power3.out'
        }, '-=0.8')
        .to('.hero-stats', { 
            opacity: 1, 
            y: 0, 
            duration: 0.8,
            ease: 'power2.out',
            stagger: 0.1
        }, '-=0.5');
    
    // Bible opening animation
    function triggerBibleAnimation() {
        gsap.delayedCall(0.5, () => {
            const bibleTl = gsap.timeline();
            
            // Divine light appears first
            bibleTl
                .to('.divine-light', {
                    opacity: 0.6,
                    scale: 1,
                    duration: 1,
                    ease: 'power2.out'
                })
                .to('.bible-animation', {
                    opacity: 1,
                    scale: 1,
                    rotateY: 0,
                    duration: 1.5,
                    ease: 'power3.out'
                }, '-=0.5')
                .to('.bible-pages', {
                    rotateY: 15,
                    duration: 2,
                    ease: 'power2.inOut',
                    yoyo: true,
                    repeat: -1
                }, '-=0.5')
                .to('.divine-light', {
                    scale: 1.2,
                    opacity: 0.3,
                    duration: 3,
                    ease: 'power2.inOut',
                    yoyo: true,
                    repeat: -1
                }, '-=1');
        });
    }
    
    // Sophisticated CTA button effects
    gsap.utils.toArray('.hero-cta .btn').forEach(btn => {
        // Golden glow effect
        gsap.to(btn, {
            boxShadow: '0 0 20px rgba(255, 215, 0, 0.5), 0 0 40px rgba(255, 215, 0, 0.3)',
            duration: 2,
            ease: 'power2.inOut',
            yoyo: true,
            repeat: -1
        });
        
        // Text shimmer effect
        const textShimmer = gsap.timeline({ repeat: -1, repeatDelay: 3 });
        textShimmer.to(btn, {
            backgroundImage: 'linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent)',
            backgroundSize: '200% 100%',
            backgroundPosition: '200% 0',
            duration: 0.8,
            ease: 'power2.inOut'
        });
    });
    
    // Divine subtitle effect
    const subtitleElement = document.querySelector('.hero-subtitle-text');
    if (subtitleElement) {
        // Add golden text glow
        gsap.to(subtitleElement, {
            textShadow: '0 0 10px rgba(255, 215, 0, 0.8), 0 0 20px rgba(255, 215, 0, 0.5)',
            duration: 3,
            ease: 'power2.inOut',
            yoyo: true,
            repeat: -1
        });
    }
    
    // Enhanced progress bar animation with divine glow
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const progressValue = progressBar.getAttribute('data-progress') || 0;
        
        // Create glowing effect
        gsap.set(progressBar, {
            boxShadow: '0 0 10px rgba(59, 130, 246, 0.5), inset 0 0 10px rgba(59, 130, 246, 0.3)'
        });
        
        gsap.fromTo(progressBar, {
            width: '0%'
        }, {
            width: progressValue + '%',
            duration: 3,
            ease: 'power2.out',
            delay: 4,
            onUpdate: function() {
                // Intensify glow as progress increases
                const progress = this.progress();
                const glowIntensity = 0.5 + (progress * 0.5);
                gsap.set(progressBar, {
                    boxShadow: `0 0 ${10 + progress * 10}px rgba(59, 130, 246, ${glowIntensity}), inset 0 0 10px rgba(59, 130, 246, 0.3)`
                });
            }
        });
    }
    
    // Enhanced counter animation with number glow
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 3;
        
        gsap.fromTo(counter, {
            textContent: 0
        }, {
            textContent: target,
            duration: duration,
            ease: 'power2.out',
            delay: 4.5,
            snap: { textContent: 1 },
            onUpdate: function() {
                counter.textContent = Math.floor(counter.textContent);
                // Add glow effect during counting
                const progress = this.progress();
                gsap.set(counter, {
                    textShadow: `0 0 ${5 + progress * 10}px rgba(255, 215, 0, ${0.3 + progress * 0.4})`
                });
            },
            onComplete: function() {
                // Final glow effect
                gsap.to(counter, {
                    textShadow: '0 0 15px rgba(255, 215, 0, 0.8)',
                    duration: 0.5,
                    yoyo: true,
                    repeat: 1
                });
            }
        });
    });
    
    // Enhanced spiritual particles
    const particles = document.querySelectorAll('.particle');
    particles.forEach((particle, index) => {
        // Make particles glow
        gsap.set(particle, {
            boxShadow: '0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.4)'
        });
        
        gsap.to(particle, {
            x: `random(-100, 100)`,
            y: `random(-100, 100)`,
            rotation: `random(0, 360)`,
            opacity: `random(0.2, 0.8)`,
            duration: `random(4, 8)`,
            ease: 'power2.inOut',
            yoyo: true,
            repeat: -1,
            delay: index * 0.2
        });
    });
    
    // Hover effects for interactive elements
    const heroCards = document.querySelectorAll('.hero-card');
    heroCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                scale: 1.05,
                y: -5,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
        
        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                scale: 1,
                y: 0,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });
    
    // Stagger animation for feature cards
    const featureCards = document.querySelectorAll('.feature-card');
    if (featureCards.length > 0) {
        gsap.fromTo(featureCards, {
            opacity: 0,
            y: 50,
            scale: 0.8
        }, {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.8,
            ease: 'power3.out',
            stagger: 0.2,
            delay: 1.5
        });
    }
});