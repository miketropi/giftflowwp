import { Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const shimmerKeyframes = `
@keyframes gf-shimmer {
  0% { background-position: -400px 0; }
  100% { background-position: 400px 0; }
}
`;

let shimmerInjected = false;

export function ensureShimmerStyles() {
	if (shimmerInjected) return;
	const style = document.createElement('style');
	style.textContent = shimmerKeyframes;
	document.head.appendChild(style);
	shimmerInjected = true;
}

export const shimmer = {
	background: 'linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%)',
	backgroundSize: '800px 100%',
	animation: 'gf-shimmer 1.8s ease-in-out infinite',
	borderRadius: 4,
};

export function ShimmerBar({ height = 14, width = '100%', borderRadius = 4, style = {} }) {
	ensureShimmerStyles();
	return (
		<div
			style={{
				height,
				width,
				...shimmer,
				borderRadius,
				...style,
			}}
		/>
	);
}

export function ShimmerBox({ height = 80, width = '100%', style = {} }) {
	ensureShimmerStyles();
	return (
		<div
			style={{
				height,
				width,
				...shimmer,
				borderRadius: 8,
				...style,
			}}
		/>
	);
}

export function ShimmerCircle({ size = 40, style = {} }) {
	ensureShimmerStyles();
	return (
		<div
			style={{
				width: size,
				height: size,
				borderRadius: '50%',
				...shimmer,
				...style,
			}}
		/>
	);
}

export function BlockPlaceholder({ icon = 'megaphone', label = '', instructions = '' }) {
	return (
		<Placeholder icon={icon} label={label || __('GiftFlow Block', 'giftflow')}>
			{instructions && (
				<p style={{ color: '#757575', margin: 0 }}>
					{instructions}
				</p>
			)}
		</Placeholder>
	);
}
