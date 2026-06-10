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
	console.log(cfg)
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
}

function initAll() {
	document.querySelectorAll( '.giftflow-st-carousel__swiper' ).forEach( initSwiper );
}

// Fire immediately if DOM is ready, otherwise wait.
if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', initAll );
} else {
	initAll();
}

// Also observe for dynamically inserted blocks (e.g. AJAX, page builders).
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
