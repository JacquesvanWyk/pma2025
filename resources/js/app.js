import './bootstrap';

// Import GSAP
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { TextPlugin } from 'gsap/TextPlugin';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger, TextPlugin);

// Make GSAP available globally
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// Import Lottie
import lottie from 'lottie-web';

// Make Lottie available globally
window.lottie = lottie;

// Import modern animations
import './animations/modern-hero';
import './animations/page-transitions';