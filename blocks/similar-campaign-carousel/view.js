import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {
	const carousels = document.querySelectorAll('.giftflow-similar-carousel__swiper');

	carousels.forEach(el => {
		const cfg = JSON.parse(el.dataset.carouselConfig || '{}');
		new Swiper(el, {
			modules: [Navigation, Pagination, Autoplay],
			...cfg,
			pagination: cfg.pagination ? { ...cfg.pagination, el: el.querySelector('.giftflow-similar-carousel__pagination') } : false,
			navigation: cfg.navigation ? {
				nextEl: el.querySelector('.giftflow-similar-carousel__next'),
				prevEl: el.querySelector('.giftflow-similar-carousel__prev'),
			} : false,
		});

		// Scroll-triggered entrance animation
		const cards = el.querySelectorAll('.giftflow-similar-carousel__item');
		if (!cards.length) return;

		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					const card = entry.target;
					const index = Array.from(cards).indexOf(card);
					const delay = index * 80; // staggered delay
					setTimeout(() => card.classList.add('is-visible'), delay);
					observer.unobserve(card);
				}
			});
		}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

		cards.forEach(card => observer.observe(card));
	});
});
