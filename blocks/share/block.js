import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('giftflow/share', {
    apiVersion: 3,
    title: __('Share Campaign', 'giftflow'),
    icon: 'share',
    category: 'giftflow',
    attributes: {
        title: { type: 'string', default: __('Share this', 'giftflow') },
        showSocials: { type: 'boolean', default: true },
        showEmail: { type: 'boolean', default: true },
        showCopyUrl: { type: 'boolean', default: true },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps({ className: 'giftflow-share' });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Settings', 'giftflow')} initialOpen={true}>
                        <TextControl label={__('Title', 'giftflow')} value={attributes.title} onChange={v => setAttributes({ title: v })} />
                    </PanelBody>
                    <PanelBody title={__('Visibility', 'giftflow')} initialOpen={false}>
                        <ToggleControl label={__('Social media', 'giftflow')} checked={attributes.showSocials} onChange={v => setAttributes({ showSocials: v })} />
                        <ToggleControl label={__('Email', 'giftflow')} checked={attributes.showEmail} onChange={v => setAttributes({ showEmail: v })} />
                        <ToggleControl label={__('Copy URL', 'giftflow')} checked={attributes.showCopyUrl} onChange={v => setAttributes({ showCopyUrl: v })} />
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {attributes.title && <span className="giftflow-share__title">{attributes.title}</span>}
                    <div className="giftflow-share__btns">
                        {attributes.showSocials && <a className="giftflow-share__btn" href="#share" onClick={e => e.preventDefault()}>{__('Facebook', 'giftflow')}</a>}
                        {attributes.showSocials && <a className="giftflow-share__btn" href="#share" onClick={e => e.preventDefault()}>X</a>}
                        {attributes.showSocials && <a className="giftflow-share__btn" href="#share" onClick={e => e.preventDefault()}>{__('LinkedIn', 'giftflow')}</a>}
                        {attributes.showEmail && <a className="giftflow-share__btn" href="#share" onClick={e => e.preventDefault()}>{__('Email', 'giftflow')}</a>}
                        {attributes.showCopyUrl && <a className="giftflow-share__btn" href="#copy" onClick={e => e.preventDefault()}>{__('Copy Link', 'giftflow')}</a>}
                    </div>
                </div>
            </>
        );
    },
});
