import './bootstrap';
import './image-preview';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function initRevealOnScroll() {
	const targets = document.querySelectorAll('.reveal-on-scroll:not([data-reveal-bound="1"])');

	if (!targets.length) {
		return;
	}

	if (!('IntersectionObserver' in window)) {
		targets.forEach((target) => {
			target.classList.add('is-visible');
			target.dataset.revealBound = '1';
		});
		return;
	}

	if (!window.__ucoRevealObserver) {
		window.__ucoRevealObserver = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					window.__ucoRevealObserver.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12 });
	}

	targets.forEach((target) => {
		target.dataset.revealBound = '1';
		window.__ucoRevealObserver.observe(target);
	});
}

window.initRevealOnScroll = initRevealOnScroll;

// Global Toast Helper
window.showToast = function(message, type = 'success') {
    window.dispatchEvent(new CustomEvent('notify', {
        detail: { message, type }
    }));
};

document.addEventListener('DOMContentLoaded', () => {
	initRevealOnScroll();
});
