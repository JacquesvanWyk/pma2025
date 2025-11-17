import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Scroll-triggered animations
document.addEventListener('DOMContentLoaded', function() {
    
    // Parallax effect for hero background
    gsap.to('.hero-bg', {
        yPercent: -50,
        ease: 'none',
        scrollTrigger: {
            trigger: '.hero-section',
            start: 'top bottom',
            end: 'bottom top',
            scrub: true
        }
    });
    
    // Fade in sections on scroll
    gsap.utils.toArray('.fade-in-section').forEach(section => {
        gsap.fromTo(section, {
            opacity: 0,
            y: 50
        }, {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });
    });
    
    // Studies cards animation
    gsap.utils.toArray('.study-card').forEach((card, index) => {
        gsap.fromTo(card, {
            opacity: 0,
            y: 30,
            scale: 0.9
        }, {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.6,
            ease: 'power2.out',
            delay: index * 0.1,
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            }
        });
    });
    
    // Resources cards animation
    gsap.utils.toArray('.resource-card').forEach((card, index) => {
        gsap.fromTo(card, {
            opacity: 0,
            x: index % 2 === 0 ? -50 : 50,
            rotation: index % 2 === 0 ? -5 : 5
        }, {
            opacity: 1,
            x: 0,
            rotation: 0,
            duration: 0.8,
            ease: 'power3.out',
            delay: index * 0.1,
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            }
        });
    });
    
    // Sermon cards animation
    gsap.utils.toArray('.sermon-card').forEach((card, index) => {
        gsap.fromTo(card, {
            opacity: 0,
            y: 40,
            scale: 0.8
        }, {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.7,
            ease: 'back.out(1.7)',
            delay: index * 0.15,
            scrollTrigger: {
                trigger: card,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });
    });
    
    // Progress bar animation on scroll
    gsap.utils.toArray('.progress-bar').forEach(bar => {
        const progress = bar.getAttribute('data-progress') || 0;
        
        gsap.fromTo(bar, {
            width: '0%'
        }, {
            width: progress + '%',
            duration: 2,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: bar,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });
    });
    
    // Text reveal animation
    gsap.utils.toArray('.text-reveal').forEach(text => {
        gsap.fromTo(text, {
            opacity: 0,
            y: 20
        }, {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: text,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            }
        });
    });
    
    // Pin the monthly pledge progress section
    ScrollTrigger.create({
        trigger: '.pledge-section',
        start: 'top center',
        end: 'bottom center',
        pin: true,
        pinSpacing: false,
        onEnter: () => {
            gsap.to('.pledge-progress', {
                scale: 1.1,
                duration: 0.5,
                ease: 'power2.out'
            });
        },
        onLeave: () => {
            gsap.to('.pledge-progress', {
                scale: 1,
                duration: 0.5,
                ease: 'power2.out'
            });
        }
    });
    
    // Navbar animation on scroll
    ScrollTrigger.create({
        start: 'top -80',
        end: 99999,
        toggleClass: {
            className: 'scrolled',
            targets: '.navbar'
        }
    });
    
    // Button hover effects
    gsap.utils.toArray('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            gsap.to(btn, {
                scale: 1.05,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
        
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, {
                scale: 1,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });
    
    // Smooth scroll for anchor links
    gsap.utils.toArray('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                gsap.to(window, {
                    duration: 1,
                    scrollTo: target,
                    ease: 'power2.inOut'
                });
            }
        });
    });
    
    // Refresh ScrollTrigger on window resize
    window.addEventListener('resize', () => {
        ScrollTrigger.refresh();
    });
});