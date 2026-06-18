import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useCampaignSelector } from '../_editor-utils';

registerBlockType('giftflow/campaign-location', {
	apiVersion: 3,
	title: __('Campaign Location', 'giftflow'),
	icon: 'location',
	category: 'giftflow',
	usesContext: ['postId', 'postType'],
	attributes: {
		campaignId: { type: 'number', default: 0 },
		showMap: { type: 'boolean', default: false },
	},
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps({ className: 'giftflow-campaign-location' });

		const { selectedCampaign: selected, CampaignSelector } = useCampaignSelector({
			defaultLabel: __('Use Current Post', 'giftflow'),
			showSelected: true,
			selectedId: attributes.campaignId || 0,
		});

		const style = attributes.style || {};
		const color = style.color || {};
		const textColor = color.text;

		const locationPin = (
			<svg
				className="giftflow-campaign-location__icon"
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 24 24"
				fill="none"
				stroke="currentColor"
				strokeWidth="2"
				strokeLinecap="round"
				strokeLinejoin="round"
				aria-hidden="true"
			>
				<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
				<circle cx="12" cy="10" r="3" />
			</svg>
		);

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Campaign', 'giftflow')}>
						<CampaignSelector
							value={attributes.campaignId}
							onChange={(v) => setAttributes({ campaignId: v })}
							label={__('Target campaign', 'giftflow')}
						/>
					</PanelBody>
					<PanelBody title={__('Settings', 'giftflow')} initialOpen={true}>
						<ToggleControl
							label={__('Show map', 'giftflow')}
							help={__('Display an embedded map below the location address.', 'giftflow')}
							checked={attributes.showMap}
							onChange={(v) => setAttributes({ showMap: v })}
						/>
					</PanelBody>
					<PanelBody title={__('About', 'giftflow')} initialOpen={false}>
						<p style={{ color: '#757575', fontSize: 13 }}>
							{__('Displays the location set in the campaign details. Choose a target campaign or leave empty to use the current page.', 'giftflow')}
						</p>
					</PanelBody>
				</InspectorControls>
				<div {...blockProps}>
					{selected && (
						<div style={{ fontSize: 11.5, fontWeight: 500, color: '#3b82f6', marginBottom: 8, display: 'flex', alignItems: 'center', gap: 5 }}>
							<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><polyline points="20 6 9 17 4 12" /></svg>
							{selected.title.rendered}
						</div>
					)}
					<div className="giftflow-campaign-location__inner">
						{locationPin}
						<span className="giftflow-campaign-location__text" style={textColor ? { color: textColor } : undefined}>
							{__('New York, United States', 'giftflow')}
						</span>
					</div>
				</div>
			</>
		);
	},
});
