/**
 * Webpack configuration for @wordpress/scripts.
 *
 * Uses wp-scripts defaults for React/JSX/SASS handling.
 * Manually adds entry points for blocks, admin, and settings.
 */
const path = require('path');
const fs = require('fs');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,

	externals: {
		...defaultConfig.externals,
		jquery: 'jQuery',
	},

	entry: {
		// Admin bundle
		'admin': path.resolve(process.cwd(), 'admin/js/admin.js'),

		// Settings React app
		'settings': path.resolve(process.cwd(), 'settings/index.js'),

		// Frontend common (includes forms, gateways, donation button, modal, share, etc.)
		'frontend-common': path.resolve(process.cwd(), 'assets/js/common.js'),

		// Block editor scripts
		'blocks/donation-button': path.resolve(process.cwd(), 'blocks/donation-button/block.js'),
		'blocks/campaign-status-bar': path.resolve(process.cwd(), 'blocks/campaign-status-bar/block.js'),
		'blocks/campaign-single-content': path.resolve(process.cwd(), 'blocks/campaign-single-content/block.js'),
		'blocks/campaign-single-images': path.resolve(process.cwd(), 'blocks/campaign-single-images/block.js'),
		'blocks/campaign-single-images-view': path.resolve(process.cwd(), 'blocks/campaign-single-images/view.js'),
		'blocks/campaigns-grid': path.resolve(process.cwd(), 'blocks/campaigns-grid/block.js'),
		'blocks/campaigns-carousel': path.resolve(process.cwd(), 'blocks/campaigns-carousel/block.js'),
		'blocks/campaigns-carousel-view': path.resolve(process.cwd(), 'blocks/campaigns-carousel/view.js'),
		'blocks/similar-campaign-carousel': path.resolve(process.cwd(), 'blocks/similar-campaign-carousel/block.js'),
		'blocks/similar-campaign-carousel-view': path.resolve(process.cwd(), 'blocks/similar-campaign-carousel/view.js'),
		'blocks/donor-account': path.resolve(process.cwd(), 'blocks/donor-account/block.js'),
		'blocks/share': path.resolve(process.cwd(), 'blocks/share/block.js'),
		'blocks/thank-donor': path.resolve(process.cwd(), 'blocks/thank-donor/block.js'),
		'blocks/campaign-location': path.resolve(process.cwd(), 'blocks/campaign-location/block.js'),
		'blocks/volunteer-cta': path.resolve(process.cwd(), 'blocks/volunteer-cta/block.js'),
		'blocks/donation-faqs': path.resolve(process.cwd(), 'blocks/donation-faqs/block.js'),
		'blocks/featured-campaign': path.resolve(process.cwd(), 'blocks/featured-campaign/block.js'),
		'blocks/featured-campaign-view': path.resolve(process.cwd(), 'blocks/featured-campaign/view.js'),
		'blocks/sponsor-logos': path.resolve(process.cwd(), 'blocks/sponsor-logos/block.js'),
		'blocks/campaign-card': path.resolve(process.cwd(), 'blocks/campaign-card/block.js'),
	},

	output: {
		path: path.resolve(process.cwd(), 'build'),
		filename: '[name].js',
		clean: false,
	},
};
