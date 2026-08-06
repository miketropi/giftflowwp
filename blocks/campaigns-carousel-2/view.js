import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';

/**
 * Campaign Carousel 2 — Swiper frontend script
 *
 * Initializes Swiper on .gf-cc elements with responsive breakpoints,
 * then runs IntersectionObserver for entrance animations.
 *
 * @package GiftFlow
 * @subpackage Blocks\CampaignsCarousel2
 */
(function () {
	document.documentElement.classList.add('gf-js');

	function init(root) {
		var swiperEl = root.querySelector('.swiper');
		if (!swiperEl || swiperEl.classList.contains('swiper-initialized')) return;

		var prevEl = root.querySelector('.gf-cc-prev');
		var nextEl = root.querySelector('.gf-cc-next');
		var pagEl = root.querySelector('.gf-cc-dots');

		var slidesPerView = parseInt(swiperEl.dataset.slidesPerView, 10) || 3;
		var gap = parseInt(getComputedStyle(root).getPropertyValue('--gf-cc-gap'), 10) || 24;

		new Swiper(swiperEl, {
			modules: [Navigation, Pagination],
			slidesPerView: 1,
			spaceBetween: gap,
			watchSlidesProgress: true,
			navigation: prevEl && nextEl ? {
				prevEl: prevEl,
				nextEl: nextEl,
				disabledClass: 'gf-cc-btn--disabled',
			} : false,
			pagination: pagEl ? {
				el: pagEl,
				clickable: true,
				bulletClass: 'gf-cc-dot',
				bulletActiveClass: 'gf-cc-dot--active',
			} : false,
			breakpoints: slidesPerView >= 3 ? {
				769: { slidesPerView: Math.min(slidesPerView, 4) > 2 ? 2 : 1, spaceBetween: gap },
				1025: { slidesPerView: slidesPerView, spaceBetween: gap },
			} : slidesPerView === 2 ? {
				769: { slidesPerView: 2, spaceBetween: gap },
			} : undefined,
		});

		// Entrance animation — independent of Swiper
		var slides = root.querySelectorAll('.gf-cc-card');
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		slides.forEach(function (slide, i) {
			slide.style.setProperty('--gf-cc-i', String(Math.min(i, 4)));
		});

		if (!('IntersectionObserver' in window) || reduce) {
			slides.forEach(function (s) { s.classList.add('gf-cc-visible'); });
			return;
		}

		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('gf-cc-visible');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.3 });

		slides.forEach(function (s) { io.observe(s); });
	}

	function initAll() {
		document.querySelectorAll('.gf-cc').forEach(init);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAll);
	} else {
		initAll();
	}

	if (window.MutationObserver) {
		var observer = new MutationObserver(function (mutations) {
			for (var i = 0; i < mutations.length; i++) {
				var nodes = mutations[i].addedNodes;
				for (var j = 0; j < nodes.length; j++) {
					var node = nodes[j];
					if (node.nodeType === 1) {
						if (node.classList && node.classList.contains('gf-cc')) {
							init(node);
						} else if (node.querySelectorAll) {
							node.querySelectorAll('.gf-cc').forEach(init);
						}
					}
				}
			}
		});
		observer.observe(document.body, { childList: true, subtree: true });
	}
})();
