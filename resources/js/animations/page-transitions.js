import { gsap } from 'gsap';

// Page transition animations
document.addEventListener('DOMContentLoaded', function() {
    
    // Page load animation
    const pageContent = document.querySelector('.page-content');

    if (pageContent) {
        const pageLoadTl = gsap.timeline();

        // Set initial state
        gsap.set('body', { opacity: 0 });

        // Animate page in
        pageLoadTl
            .to('body', {
                opacity: 1,
                duration: 0.5,
                ease: 'power2.out'
            })
            .fromTo(pageContent, {
                y: 30,
                opacity: 0
            }, {
                y: 0,
                opacity: 1,
                duration: 0.8,
                ease: 'power3.out'
            }, '-=0.3');
    } else {
        // If no page-content, just show the body
        gsap.set('body', { opacity: 1 });
    }
    
    // Navigation link hover effects
    gsap.utils.toArray('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', () => {
            gsap.to(link, {
                scale: 1.1,
                color: '#3B82F6',
                duration: 0.3,
                ease: 'power2.out'
            });
        });
        
        link.addEventListener('mouseleave', () => {
            gsap.to(link, {
                scale: 1,
                color: 'inherit',
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });
    
    // Card hover animations
    gsap.utils.toArray('.card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, {
                y: -10,
                boxShadow: '0 20px 40px rgba(0,0,0,0.1)',
                duration: 0.4,
                ease: 'power2.out'
            });
        });
        
        card.addEventListener('mouseleave', () => {
            gsap.to(card, {
                y: 0,
                boxShadow: '0 10px 20px rgba(0,0,0,0.05)',
                duration: 0.4,
                ease: 'power2.out'
            });
        });
    });
    
    // Form field focus animations
    gsap.utils.toArray('input, textarea').forEach(field => {
        field.addEventListener('focus', () => {
            gsap.to(field, {
                scale: 1.02,
                borderColor: '#3B82F6',
                duration: 0.3,
                ease: 'power2.out'
            });
        });
        
        field.addEventListener('blur', () => {
            gsap.to(field, {
                scale: 1,
                borderColor: 'inherit',
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });
    
    // Loading spinner animation
    const spinner = document.querySelector('.loading-spinner');
    if (spinner) {
        gsap.to(spinner, {
            rotation: 360,
            duration: 1,
            ease: 'none',
            repeat: -1
        });
    }
    
    // Modal animations
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            gsap.set(modal, { display: 'flex' });
            
            const modalTl = gsap.timeline();
            modalTl
                .fromTo(modal, {
                    opacity: 0
                }, {
                    opacity: 1,
                    duration: 0.3,
                    ease: 'power2.out'
                })
                .fromTo(modal.querySelector('.modal-content'), {
                    scale: 0.8,
                    y: 50
                }, {
                    scale: 1,
                    y: 0,
                    duration: 0.4,
                    ease: 'back.out(1.7)'
                }, '-=0.2');
        }
    };
    
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const modalTl = gsap.timeline();
            modalTl
                .to(modal.querySelector('.modal-content'), {
                    scale: 0.8,
                    y: 50,
                    duration: 0.3,
                    ease: 'power2.in'
                })
                .to(modal, {
                    opacity: 0,
                    duration: 0.2,
                    ease: 'power2.in',
                    onComplete: () => {
                        gsap.set(modal, { display: 'none' });
                    }
                }, '-=0.1');
        }
    };
    
    // Notification animations
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        gsap.fromTo(notification, {
            opacity: 0,
            y: -50,
            x: '50%'
        }, {
            opacity: 1,
            y: 20,
            duration: 0.5,
            ease: 'back.out(1.7)',
            onComplete: () => {
                gsap.to(notification, {
                    opacity: 0,
                    y: -50,
                    duration: 0.3,
                    ease: 'power2.in',
                    delay: 3,
                    onComplete: () => {
                        document.body.removeChild(notification);
                    }
                });
            }
        });
    };
    
    // Smooth page transitions for SPA-like behavior
    const pageContentForTransition = document.querySelector('.page-content');

    if (pageContentForTransition) {
        gsap.utils.toArray('a:not([href^="#"]):not([href^="mailto"]):not([href^="tel"]):not([target="_blank"])').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.hostname === window.location.hostname) {
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    gsap.to(pageContentForTransition, {
                        opacity: 0,
                        y: -30,
                        duration: 0.3,
                        ease: 'power2.in',
                        onComplete: () => {
                            window.location.href = href;
                        }
                    });
                }
            });
        });
    }
    
    // Refresh animations on route change (if using Livewire or similar)
    document.addEventListener('livewire:load', function() {
        ScrollTrigger.refresh();
    });
    
    document.addEventListener('livewire:update', function() {
        ScrollTrigger.refresh();
    });
});