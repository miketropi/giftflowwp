import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextareaControl, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('giftflow/thank-donor', {
    apiVersion: 3,
    title: __('Thank Donor', 'giftflow'),
    icon: 'yes-alt',
    category: 'giftflow',
    attributes: {
        heading: { type: 'string', default: __('Thank You!', 'giftflow') },
        message: { type: 'string', default: __('Your donation has been received. We appreciate your support!', 'giftflow') },
        accountNotice: { type: 'string', default: __("We've created an account for you. Your login details have been sent to your inbox.", 'giftflow') },
        showAccountNotice: { type: 'boolean', default: true },
        buttonText: { type: 'string', default: __('View My Donations', 'giftflow') },
        buttonUrl: { type: 'string', default: '' },
        showButton: { type: 'boolean', default: true },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps({ className: 'giftflow-thank-donor' });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Content', 'giftflow')} initialOpen>
                        <TextControl label={__('Heading', 'giftflow')} value={attributes.heading} onChange={v => setAttributes({ heading: v })} />
                        <TextareaControl label={__('Message', 'giftflow')} value={attributes.message} onChange={v => setAttributes({ message: v })} rows={3} />
                        <ToggleControl label={__('Show account notice', 'giftflow')} checked={attributes.showAccountNotice} onChange={v => setAttributes({ showAccountNotice: v })} />
                        {attributes.showAccountNotice && <TextareaControl label={__('Account notice', 'giftflow')} value={attributes.accountNotice} onChange={v => setAttributes({ accountNotice: v })} rows={4} help={__('Basic HTML allowed.', 'giftflow')} />}
                    </PanelBody>
                    <PanelBody title={__('Button', 'giftflow')} initialOpen={false}>
                        <ToggleControl label={__('Show button', 'giftflow')} checked={attributes.showButton} onChange={v => setAttributes({ showButton: v })} />
                        {attributes.showButton && <>
                            <TextControl label={__('Button text', 'giftflow')} value={attributes.buttonText} onChange={v => setAttributes({ buttonText: v })} />
                            <TextControl label={__('Button URL', 'giftflow')} value={attributes.buttonUrl} onChange={v => setAttributes({ buttonUrl: v })} type="url" help={__('Leave empty for default donor account link.', 'giftflow')} />
                        </>}
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    <div className="giftflow-thank-donor__icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                    </div>
                    <h2 className="giftflow-thank-donor__heading">{attributes.heading}</h2>
                    <div className="giftflow-thank-donor__message"><p>{attributes.message}</p></div>
                    {attributes.showAccountNotice && attributes.accountNotice && (
                        <div className="giftflow-thank-donor__notice"><p>{attributes.accountNotice}</p></div>
                    )}
                    {attributes.showButton && attributes.buttonText && (
                        <div className="giftflow-thank-donor__action">
                            <span className="giftflow-thank-donor__btn">{attributes.buttonText}</span>
                        </div>
                    )}
                </div>
            </>
        );
    },
});
