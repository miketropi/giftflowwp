import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.giftflow-carousel__swiper').forEach(el => {
		const cfg = JSON.parse(el.dataset.carouselConfig || '{}');
		new Swiper(el, {
			modules: [Navigation, Pagination, Autoplay],
			...cfg,
			pagination: cfg.pagination ? { ...cfg.pagination, el: el.querySelector('.giftflow-carousel__pagination') } : false,
			navigation: cfg.navigation ? {
				nextEl: el.querySelector('.giftflow-carousel__next'),
				prevEl: el.querySelector('.giftflow-carousel__prev'),
			} : false,
		});
	});
});
