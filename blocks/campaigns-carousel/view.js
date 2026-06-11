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
			? { ...cfg.pagination, el: el.querySelector( '.giftflow-carousel__pagination' ) }
			: false,
		navigation: cfg.navigation
			? {
					nextEl: el.querySelector( '.giftflow-carousel__next' ),
					prevEl: el.querySelector( '.giftflow-carousel__prev' ),
			  }
			: false,
	} );
}

function initAll() {
	document.querySelectorAll( '.giftflow-carousel__swiper' ).forEach( initSwiper );
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
					if ( node.classList && node.classList.contains( 'giftflow-carousel__swiper' ) ) {
						initSwiper( node );
					} else if ( node.querySelectorAll ) {
						node.querySelectorAll( '.giftflow-carousel__swiper' ).forEach( initSwiper );
					}
				}
			}
		}
	} );
	observer.observe( document.body, { childList: true, subtree: true } );
}
