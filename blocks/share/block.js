import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Disabled, PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('giftflowwp/share', {
    apiVersion: 3,
    title: 'Share',
    icon: 'share',
    category: 'giftflowwp',
    attributes: {
        title: {
            type: 'string',
            default: 'Share this',
        },
        customUrl: {
            type: 'string',
            default: '',
        },
        showSocials: {
            type: 'boolean',
            default: true,
        },
        showEmail: {
            type: 'boolean',
            default: true,
        },
        showCopyUrl: {
            type: 'boolean',
            default: true,
        },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps();

        const updateAttribute = (key, value) => {
            setAttributes({ [key]: value });
        };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Share Settings', 'giftflowwp')} initialOpen={true}>
                        <TextControl
                            label={__('Title', 'giftflowwp')}
                            value={attributes.title}
                            onChange={(value) => updateAttribute('title', value)}
                            help={__('Heading text above the share buttons.', 'giftflowwp')}
                        />
                        <TextControl
                            label={__('Custom URL', 'giftflowwp')}
                            value={attributes.customUrl}
                            onChange={(value) => updateAttribute('customUrl', value)}
                            help={__('Leave empty to use current page URL, or enter a custom URL to share.', 'giftflowwp')}
                        />
                    </PanelBody>

                    <PanelBody title={__('Sharing Options', 'giftflowwp')} initialOpen={true}>
                        <ToggleControl
                            label={__('Social Media (Facebook, X, LinkedIn)', 'giftflowwp')}
                            checked={attributes.showSocials}
                            onChange={(value) => updateAttribute('showSocials', value)}
                            help={__('Display social media sharing buttons.', 'giftflowwp')}
                        />
                        <ToggleControl
                            label={__('Email', 'giftflowwp')}
                            checked={attributes.showEmail}
                            onChange={(value) => updateAttribute('showEmail', value)}
                            help={__('Display email sharing option.', 'giftflowwp')}
                        />
                        <ToggleControl
                            label={__('Copy URL', 'giftflowwp')}
                            checked={attributes.showCopyUrl}
                            onChange={(value) => updateAttribute('showCopyUrl', value)}
                            help={__('Display copy URL to clipboard button.', 'giftflowwp')}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <Disabled>
                        <ServerSideRender 
                            block="giftflowwp/share" 
                            attributes={attributes} />
                    </Disabled>
                </div>
            </>
        );
    },
});
