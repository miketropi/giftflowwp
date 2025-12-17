import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Disabled, PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('giftflow/share', {
    apiVersion: 3,
    title: 'Share',
    icon: 'share',
    category: 'giftflow',
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
                    <PanelBody title={__('Share Settings', 'giftflow')} initialOpen={true}>
                        <TextControl
                            label={__('Title', 'giftflow')}
                            value={attributes.title}
                            onChange={(value) => updateAttribute('title', value)}
                            help={__('Heading text above the share buttons.', 'giftflow')}
                        />
                        <TextControl
                            label={__('Custom URL', 'giftflow')}
                            value={attributes.customUrl}
                            onChange={(value) => updateAttribute('customUrl', value)}
                            help={__('Leave empty to use current page URL, or enter a custom URL to share.', 'giftflow')}
                        />
                    </PanelBody>

                    <PanelBody title={__('Sharing Options', 'giftflow')} initialOpen={true}>
                        <ToggleControl
                            label={__('Social Media (Facebook, X, LinkedIn)', 'giftflow')}
                            checked={attributes.showSocials}
                            onChange={(value) => updateAttribute('showSocials', value)}
                            help={__('Display social media sharing buttons.', 'giftflow')}
                        />
                        <ToggleControl
                            label={__('Email', 'giftflow')}
                            checked={attributes.showEmail}
                            onChange={(value) => updateAttribute('showEmail', value)}
                            help={__('Display email sharing option.', 'giftflow')}
                        />
                        <ToggleControl
                            label={__('Copy URL', 'giftflow')}
                            checked={attributes.showCopyUrl}
                            onChange={(value) => updateAttribute('showCopyUrl', value)}
                            help={__('Display copy URL to clipboard button.', 'giftflow')}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <Disabled>
                        <ServerSideRender 
                            block="giftflow/share" 
                            attributes={attributes} />
                    </Disabled>
                </div>
            </>
        );
    },
});
