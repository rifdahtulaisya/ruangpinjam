import './bootstrap';

import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';

window.Alpine = Alpine;

Alpine.start();

AOS.init({
    duration: 900,
    easing: 'ease-out-cubic',
    once: true,
});
