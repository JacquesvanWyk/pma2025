import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Modern Award-Winning Hero Animations
document.addEventListener('DOMContentLoaded', function() {
    
    // Custom split text function (since SplitText is premium)
    function splitText(element, type = 'words') {
        const text = element.textContent;
        element.innerHTML = '';
        
        if (type === 'chars') {
            text.split('').forEach(char => {
                const span = document.createElement('span');
                span.textContent = char === ' ' ? '\u00A0' : char;
                span.style.display = 'inline-block';
                element.appendChild(span);
            });
        } else {
            text.split(' ').forEach((word, index) => {
                const span = document.createElement('span');
                span.textContent = word;
                span.style.display = 'inline-block';
                span.style.marginRight = '0.25em';
                element.appendChild(span);
            });
        }
        
        return Array.from(element.children);
    }
    
    // Only initialize if hero elements exist
    if (document.querySelector('.hero-section-modern') || document.querySelector('.hero-content')) {
        initModernHero();
        initCinematicScrolling();
        initMagneticEffects();
        initMorphingBackground();
    }

    function initModernHero() {
        const heroContent = document.querySelector('.hero-content');
        const heroBgShape = document.querySelector('.hero-bg-shape');

        if (!heroContent && !heroBgShape) return;

        // Set initial states
        if (heroContent) gsap.set(heroContent, { opacity: 0 });
        if (heroBgShape) gsap.set(heroBgShape, { scale: 0, rotation: 45 });
        
        // Create master timeline
        const tl = gsap.timeline({ delay: 0.5 });

        // Morphing background reveal
        if (heroBgShape) {
            tl.to(heroBgShape, {
                scale: 1,
                rotation: 0,
                duration: 2,
                ease: 'power4.out'
            });
        }

        // Content fade in
        if (heroContent) {
            tl.to(heroContent, {
                opacity: 1,
                duration: 1,
                ease: 'power2.out'
            }, '-=1.5');
        }
        
        // Split and animate title
        const titleElement = document.querySelector('.hero-title-modern');
        if (titleElement) {
            const words = splitText(titleElement, 'words');
            
            gsap.set(words, { 
                opacity: 0, 
                y: 100,
                rotationX: 90
            });
            
            tl.to(words, {
                opacity: 1,
                y: 0,
                rotationX: 0,
                duration: 1.2,
                stagger: 0.08,
                ease: 'power4.out'
            }, '-=0.8');
        }
        
        // Animate subtitle with elegant reveal
        const subtitle = document.querySelector('.hero-subtitle-modern');
        if (subtitle) {
            gsap.set(subtitle, { 
                opacity: 0, 
                y: 50,
                clipPath: 'inset(100% 0 0 0)'
            });
            
            tl.to(subtitle, {
                opacity: 1,
                y: 0,
                clipPath: 'inset(0% 0 0 0)',
                duration: 1.5,
                ease: 'power3.out'
            }, '-=0.5');
        }
        
        // Animate description with letter spacing effect
        const description = document.querySelector('.hero-description-modern');
        if (description) {
            gsap.set(description, { 
                opacity: 0, 
                letterSpacing: '10px',
                y: 30
            });
            
            tl.to(description, {
                opacity: 1,
                letterSpacing: '0px',
                y: 0,
                duration: 1.8,
                ease: 'power3.out'
            }, '-=1');
        }
        
        // Animate CTAs with sophisticated hover states
        const ctas = document.querySelectorAll('.hero-cta-modern .btn');
        if (ctas.length) {
            gsap.set(ctas, { 
                opacity: 0, 
                scale: 0.8,
                y: 50
            });
            
            tl.to(ctas, {
                opacity: 1,
                scale: 1,
                y: 0,
                duration: 1,
                stagger: 0.1,
                ease: 'back.out(1.7)'
            }, '-=0.8');
        }
        
        // Animate progress section with modern reveal
        const progressSection = document.querySelector('.progress-modern');
        if (progressSection) {
            gsap.set(progressSection, { 
                opacity: 0, 
                y: 30,
                scale: 0.95
            });
            
            tl.to(progressSection, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 1.2,
                ease: 'power3.out'
            }, '-=0.6');
            
            // Animate progress bar with modern effect
            const progressBar = progressSection.querySelector('.progress-bar-modern');
            const progressValue = progressBar?.getAttribute('data-progress') || 0;
            
            if (progressBar) {
                gsap.set(progressBar, { width: '0%' });
                
                tl.to(progressBar, {
                    width: progressValue + '%',
                    duration: 2,
                    ease: 'power2.out'
                }, '-=0.3');
            }
            
            // Counter animation with modern styling
            const counter = progressSection.querySelector('.counter-modern');
            const target = counter?.getAttribute('data-target') || 0;
            
            if (counter) {
                tl.to(counter, {
                    textContent: target,
                    duration: 2,
                    ease: 'power2.out',
                    snap: { textContent: 1 },
                    onUpdate: function() {
                        counter.textContent = Math.floor(counter.textContent);
                    }
                }, '-=1.5');
            }
        }
    }
    
    function initCinematicScrolling() {
        const heroSection = document.querySelector('.hero-section-modern');
        const heroBgParallax = document.querySelector('.hero-bg-parallax');
        const heroContentScale = document.querySelector('.hero-content-scale');

        // Parallax effect for hero background
        if (heroBgParallax && heroSection) {
            gsap.to(heroBgParallax, {
                yPercent: -30,
                ease: 'none',
                scrollTrigger: {
                    trigger: heroSection,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 1
                }
            });
        }

        // Scale effect on hero content
        if (heroContentScale && heroSection) {
            gsap.to(heroContentScale, {
                scale: 0.8,
                opacity: 0.3,
                ease: 'none',
                scrollTrigger: {
                    trigger: heroSection,
                    start: 'center top',
                    end: 'bottom top',
                    scrub: 1
                }
            });
        }
        
        // Reveal sections with cinematic timing
        gsap.utils.toArray('.reveal-section').forEach(section => {
            gsap.fromTo(section, {
                opacity: 0,
                y: 100,
                scale: 0.95
            }, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 1.5,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 80%',
                    toggleActions: 'play none none reverse'
                }
            });
        });
    }
    
    function initMagneticEffects() {
        const magneticElements = document.querySelectorAll('.magnetic');
        
        magneticElements.forEach(element => {
            let rect = element.getBoundingClientRect();
            
            element.addEventListener('mouseenter', () => {
                gsap.to(element, {
                    scale: 1.05,
                    duration: 0.4,
                    ease: 'power2.out'
                });
            });
            
            element.addEventListener('mouseleave', () => {
                gsap.to(element, {
                    scale: 1,
                    x: 0,
                    y: 0,
                    duration: 0.6,
                    ease: 'elastic.out(1, 0.3)'
                });
            });
            
            element.addEventListener('mousemove', (e) => {
                rect = element.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                gsap.to(element, {
                    x: x * 0.15,
                    y: y * 0.15,
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });
            
            // Update rect on resize
            window.addEventListener('resize', () => {
                rect = element.getBoundingClientRect();
            });
        });
    }
    
    function initMorphingBackground() {
        const morphingGradient = document.querySelector('.morphing-gradient');

        if (morphingGradient) {
            // Create morphing gradient background
            const gradientTl = gsap.timeline({ repeat: -1, yoyo: true });

            gradientTl
                .to(morphingGradient, {
                    background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    duration: 8,
                    ease: 'power2.inOut'
                })
                .to(morphingGradient, {
                    background: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    duration: 8,
                    ease: 'power2.inOut'
                })
                .to(morphingGradient, {
                    background: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    duration: 8,
                    ease: 'power2.inOut'
                });
        }
        
        // Floating elements with sophisticated movement
        gsap.utils.toArray('.floating-element').forEach((element, index) => {
            gsap.to(element, {
                y: 'random(-30, 30)',
                x: 'random(-20, 20)',
                rotation: 'random(-15, 15)',
                duration: 'random(4, 8)',
                ease: 'power2.inOut',
                yoyo: true,
                repeat: -1,
                delay: index * 0.5
            });
        });
    }
    
    // Modern card animations
    gsap.utils.toArray('.modern-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                y: -20,
                rotationY: 5,
                rotationX: 5,
                boxShadow: '0 30px 60px rgba(0,0,0,0.15)',
                duration: 0.6,
                ease: 'power3.out'
            });
        });
        
        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                y: 0,
                rotationY: 0,
                rotationX: 0,
                boxShadow: '0 10px 30px rgba(0,0,0,0.1)',
                duration: 0.6,
                ease: 'power3.out'
            });
        });
    });
    
    // Smooth scroll for navigation
    gsap.utils.toArray('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                gsap.to(window, {
                    duration: 1.5,
                    scrollTo: target,
                    ease: 'power3.inOut'
                });
            }
        });
    });
});