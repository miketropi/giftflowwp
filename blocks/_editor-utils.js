import { Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';

const { useSelect } = wp.data;

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

/**
 * Shared hook: fetch published campaigns and build SelectControl options.
 *
 * @param {Object}  opts
 * @param {string}  [opts.defaultLabel]  Label for the "no selection" option (e.g., "Select a campaign…").
 * @param {number}  [opts.defaultValue]  Value for the "no selection" option (default 0).
 * @param {boolean} [opts.showSelected]  Whether to return the selected campaign object.
 * @param {number}  [opts.selectedId]    Current campaign ID to look up.
 * @return {{ campaigns: Array|null, campaignOptions: Array, selectedCampaign: Object|null, CampaignSelector: JSX.Element }}
 */
export function useCampaignSelector(opts = {}) {
	const { defaultLabel, defaultValue = 0, showSelected = false, selectedId = 0 } = opts;

	const campaigns = useSelect(
		(select) => select('core').getEntityRecords('postType', 'campaign', { per_page: -1, status: 'publish' }),
		[]
	);

	const campaignOptions = campaigns
		? [{ label: defaultLabel || __('Select a campaign…', 'giftflow'), value: defaultValue }, ...campaigns.map((c) => ({ label: c.title.rendered, value: c.id }))]
		: [{ label: __('Loading…', 'giftflow'), value: defaultValue }];

	const selectedCampaign = showSelected && campaigns && selectedId > 0
		? campaigns.find((c) => c.id === selectedId) || null
		: null;

	/**
	 * Pre-built SelectControl for the campaign chooser.
	 * @param {Object}   props
	 * @param {number}   props.value    Current campaignId attribute value.
	 * @param {Function} props.onChange Called with the new numeric campaign ID.
	 * @param {string}   [props.label]  SelectControl label text.
	 * @param {string}   [props.help]   SelectControl help text.
	 */
	const CampaignSelector = ({ value, onChange, label, help }) => (
		<SelectControl
			label={label || __('Campaign', 'giftflow')}
			value={value}
			options={campaignOptions}
			onChange={(v) => onChange(parseInt(v, 10))}
			help={help || ''}
			__nextHasNoMarginBottom
		/>
	);

	return { campaigns, campaignOptions, selectedCampaign, CampaignSelector };
}
