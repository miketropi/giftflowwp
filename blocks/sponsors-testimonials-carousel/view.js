import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';

function revealCards( el, delay = 0 ) {
	const cards = el.querySelectorAll( '.giftflow-st-carousel__card:not(.is-visible)' );
	if ( ! cards.length ) {
		return;
	}
	cards.forEach( ( card, i ) => {
		setTimeout( () => {
			card.classList.add( 'is-visible' );
		}, delay + i * 100 );
	} );
}

function observeScroll( el ) {
	if ( el.dataset.gfStObserved ) {
		return;
	}
	el.dataset.gfStObserved = '1';

	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					revealCards( entry.target, 200 );
					observer.unobserve( entry.target );
				}
			} );
		},
		{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
	);
	observer.observe( el );
}

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
			? { ...cfg.pagination, el: el.querySelector( '.giftflow-st-carousel__pagination' ) }
			: false,
		navigation: cfg.navigation
			? {
					nextEl: el.querySelector( '.giftflow-st-carousel__next' ),
					prevEl: el.querySelector( '.giftflow-st-carousel__prev' ),
			  }
			: false,
	} );

	// Start scroll-triggered entrance animation.
	observeScroll( el );
}

function initAll() {
	document.querySelectorAll( '.giftflow-st-carousel__swiper' ).forEach( initSwiper );
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
					if ( node.classList && node.classList.contains( 'giftflow-st-carousel__swiper' ) ) {
						initSwiper( node );
					} else if ( node.querySelectorAll ) {
						node.querySelectorAll( '.giftflow-st-carousel__swiper' ).forEach( initSwiper );
					}
				}
			}
		}
	} );
	observer.observe( document.body, { childList: true, subtree: true } );
}
