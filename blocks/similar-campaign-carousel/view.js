import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';

function initSwiper( el ) {
	if ( el.classList.contains( 'swiper-initialized' ) ) {
		return;
	}
	const raw = el.dataset.carouselConfig;
	if ( ! raw ) {
		return;
	}
	let cfg;
	try {
		cfg = JSON.parse( raw );
	} catch ( e ) {
		return;
	}
	new Swiper( el, {
		modules: [ Navigation, Pagination, Autoplay ],
		...cfg,
		pagination: cfg.pagination
			? { ...cfg.pagination, el: el.querySelector( '.giftflow-similar-carousel__pagination' ) }
			: false,
		navigation: cfg.navigation
			? {
					nextEl: el.querySelector( '.giftflow-similar-carousel__next' ),
					prevEl: el.querySelector( '.giftflow-similar-carousel__prev' ),
			  }
			: false,
	} );

	// Scroll-triggered entrance animation
	const cards = el.querySelectorAll( '.giftflow-similar-carousel__item' );
	if ( ! cards.length ) return;

	const observer = new IntersectionObserver( ( entries ) => {
		entries.forEach( ( entry ) => {
			if ( entry.isIntersecting ) {
				const card = entry.target;
				const index = Array.from( cards ).indexOf( card );
				const delay = index * 80;
				setTimeout( () => card.classList.add( 'is-visible' ), delay );
				observer.unobserve( card );
			}
		} );
	}, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' } );

	cards.forEach( ( card ) => observer.observe( card ) );
}

function initAll() {
	document.querySelectorAll( '.giftflow-similar-carousel__swiper' ).forEach( initSwiper );
}

if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', initAll );
} else {
	initAll();
}

if ( window.MutationObserver ) {
	const observer = new MutationObserver( ( mutations ) => {
		for ( const m of mutations ) {
			for ( const node of m.addedNodes ) {
				if ( node.nodeType === 1 ) {
					if ( node.classList && node.classList.contains( 'giftflow-similar-carousel__swiper' ) ) {
						initSwiper( node );
					} else if ( node.querySelectorAll ) {
						node.querySelectorAll( '.giftflow-similar-carousel__swiper' ).forEach( initSwiper );
					}
				}
			}
		}
	} );
	observer.observe( document.body, { childList: true, subtree: true } );
}
