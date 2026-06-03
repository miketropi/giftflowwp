import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('giftflow/campaign-location', {
	apiVersion: 3,
	title: __('Campaign Location', 'giftflow'),
	icon: 'location',
	category: 'giftflow',
	usesContext: ['postId', 'postType'],
	attributes: {
		showMap: { type: 'boolean', default: false },
	},
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps({ className: 'giftflow-campaign-location' });

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
							{__('Displays the location set in the campaign details. The location metadata is read from the current campaign page.', 'giftflow')}
						</p>
					</PanelBody>
				</InspectorControls>
				<div {...blockProps}>
					<div className="giftflow-campaign-location__inner">
						{locationPin}
						<span className="giftflow-campaign-location__text">
							{__('New York, United States', 'giftflow')}
						</span>
					</div>
				</div>
			</>
		);
	},
});
