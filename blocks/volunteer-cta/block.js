import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('giftflow/volunteer-cta', {
	apiVersion: 3,
	title: __('Volunteer CTA', 'giftflow'),
	icon: 'heart',
	category: 'giftflow',
	attributes: {
		heading: { type: 'string', default: __('Become a Volunteer', 'giftflow') },
		description: { type: 'string', default: __('Join our community of volunteers and make a lasting impact. Your time and skills can change lives in meaningful ways.', 'giftflow') },
		buttonText: { type: 'string', default: __('Register Now', 'giftflow') },
		buttonUrl: { type: 'string', default: '' },
		showIcon: { type: 'boolean', default: true },
	},
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps({ className: 'giftflow-volunteer-cta' });

		const heartIcon = (
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 24 24"
				fill="none"
				stroke="currentColor"
				strokeWidth="2"
				strokeLinecap="round"
				strokeLinejoin="round"
				aria-hidden="true"
			>
				<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
			</svg>
		);

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Content', 'giftflow')} initialOpen={true}>
						<TextControl
							label={__('Button URL', 'giftflow')}
							value={attributes.buttonUrl}
							onChange={(v) => setAttributes({ buttonUrl: v })}
							placeholder="https://"
						/>
						<ToggleControl
							label={__('Show icon', 'giftflow')}
							checked={attributes.showIcon}
							onChange={(v) => setAttributes({ showIcon: v })}
						/>
					</PanelBody>
					<PanelBody title={__('About', 'giftflow')} initialOpen={false}>
						<p style={{ color: '#757575', fontSize: 13 }}>
							{__('A call-to-action section to encourage visitors to register as volunteers. Place it anywhere on your site — campaign pages, landing pages, or sidebars.', 'giftflow')}
						</p>
					</PanelBody>
				</InspectorControls>
				<div {...blockProps}>
					{attributes.showIcon && (
						<div className="giftflow-volunteer-cta__icon" aria-hidden="true">
							{heartIcon}
						</div>
					)}
					<RichText
						tagName="h2"
						className="giftflow-volunteer-cta__heading"
						value={attributes.heading}
						onChange={(v) => setAttributes({ heading: v })}
						placeholder={__('Add heading...', 'giftflow')}
						withoutInteractiveFormatting
					/>
					<RichText
						tagName="div"
						className="giftflow-volunteer-cta__description"
						value={attributes.description}
						onChange={(v) => setAttributes({ description: v })}
						placeholder={__('Add description...', 'giftflow')}
					/>
					<div className="giftflow-volunteer-cta__action">
						<RichText
							tagName="span"
							className="giftflow-volunteer-cta__button"
							value={attributes.buttonText}
							onChange={(v) => setAttributes({ buttonText: v })}
							placeholder={__('Button text...', 'giftflow')}
							withoutInteractiveFormatting
							allowedFormats={[]}
						/>
					</div>
				</div>
			</>
		);
	},
});
