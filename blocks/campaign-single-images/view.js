import gsap from 'gsap';
import { createGiftflowLightbox } from '../../assets/js/util/gfw-image-lightbox.js';

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.giftflow-campaign-images:not(.giftflow-campaign-images--empty)').forEach(initGallery);
});

function initGallery(container) {
	const mainImg   = container.querySelector('.giftflow-campaign-images__main-img');
	const thumbs  = container.querySelectorAll('.giftflow-campaign-images__thumb:not(.giftflow-campaign-images__thumb--more)');
	const counter   = container.querySelector('.giftflow-campaign-images__counter');
	const moreBtn   = container.querySelector('.giftflow-campaign-images__thumb--more');
	const mainWrap  = container.querySelector('.giftflow-campaign-images__main');
	const overlay   = container.querySelector('.giftflow-campaign-images__overlay');

	if (!mainImg) return;

	let activeIdx = 0;
	let isAnimating = false;

	// Fade-in entrance.
	gsap.fromTo(mainWrap, { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' });
	gsap.fromTo(thumbs, { opacity: 0, y: 8 }, { opacity: 1, y: 0, duration: 0.5, stagger: 0.06, ease: 'power2.out', delay: 0.15 });

	function setActive(idx) {
		if (isAnimating || idx === activeIdx) return;
		isAnimating = true;

		const prevIdx = activeIdx;
		activeIdx = idx;

		thumbs.forEach((t, i) => {
			t.classList.toggle('giftflow-campaign-images__thumb--active', i === idx);
			t.setAttribute('tabindex', i === idx ? '0' : '-1');
		});

		if (counter) {
			gsap.to(counter, { opacity: 0, duration: 0.15, onComplete: () => {
				counter.textContent = `${idx + 1} / ${thumbs.length}`;
				gsap.to(counter, { opacity: 1, duration: 0.2 });
			}});
		}

		const next = thumbs[idx];
		if (!next) { isAnimating = false; return; }
		const thumbImg = next.querySelector('img');
		const newSrc  = next.dataset.imageUrl || thumbImg?.src || '';
		const fullSrc = next.dataset.imageFullUrl || next.dataset.pswpSrc || newSrc;

		// Scale down & fade the current image.
		gsap.to(mainImg, { scale: 0.97, opacity: 0, duration: 0.2, ease: 'power2.in', onComplete: () => {
			mainImg.src = newSrc;
			mainImg.dataset.pswpSrc = fullSrc;
			// Scale up & fade the new image.
			gsap.fromTo(mainImg, { scale: 1.03, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.35, ease: 'power2.out', onComplete: () => {
				isAnimating = false;
			}});
		}});

		// Subtle pulse on the active thumb.
		gsap.fromTo(thumbs[idx], { scale: 1 }, { scale: 1.06, duration: 0.15, ease: 'power2.out', yoyo: true, repeat: 1 });
	}

	thumbs.forEach((thumb, i) => {
		thumb.addEventListener('click', () => setActive(i));
		thumb.setAttribute('tabindex', i === 0 ? '0' : '-1');
	});

	// Hover tilt on thumbnails.
	thumbs.forEach(thumb => {
		thumb.addEventListener('mouseenter', () => {
			gsap.to(thumb, { y: -2, duration: 0.25, ease: 'power2.out' });
		});
		thumb.addEventListener('mouseleave', () => {
			gsap.to(thumb, { y: 0, duration: 0.25, ease: 'power2.out' });
		});
	});

	if (moreBtn) {
		moreBtn.addEventListener('click', () => {
			const hidden = container.querySelectorAll('.giftflow-campaign-images__thumb--hidden');
			// Remove display:none so GSAP can animate, then set invisible start state.
			hidden.forEach(t => t.classList.remove('giftflow-campaign-images__thumb--hidden'));
			gsap.set(hidden, { autoAlpha: 0, y: -8 });
			gsap.to(hidden, { autoAlpha: 1, y: 0, duration: 0.4, stagger: 0.05, ease: 'power2.out' });
			gsap.to(moreBtn, { scale: 0.9, autoAlpha: 0, duration: 0.25, ease: 'power2.in', onComplete: () => moreBtn.remove() });
		});
	}

	// Lightbox.
	mainImg.addEventListener('click', () => {
		const images = Array.from(thumbs).map(t => {
			const ti = t.querySelector('img');
			return {
				src: t.dataset.imageFullUrl || t.dataset.pswpSrc || ti?.src || '',
				width: 1200,
				height: 800,
			};
		}).filter(i => i.src);

		if (images.length > 0) {
			const lb = createGiftflowLightbox({ items: images });
			lb.open(activeIdx);
		}
	});
}
