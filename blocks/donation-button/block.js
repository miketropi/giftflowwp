import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Disabled, PanelBody, SelectControl, TextControl, ColorPicker, BaseControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
const { useSelect } = wp.data;


registerBlockType('giftflowwp/donation-button', {
    apiVersion: 3,
    title: 'Donation Button',
    icon: 'heart',
    category: 'giftflowwp',
    attributes: {
        campaignId: {
            type: 'number',
            default: 0,
        },
        buttonText: {
            type: 'string',
            default: 'Donate Now',
        },
        backgroundColor: {
            type: 'string',
            default: '#000',
        },
        textColor: {
            type: 'string',
            default: '#ffffff',
        },
        borderRadius: {
            type: 'number',
            default: 0,
        },
        fullWidth: {
            type: 'boolean',
            default: false,
        },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps();

        // Get campaigns for the dropdown
        const campaigns = useSelect((select) => {
            return select('core').getEntityRecords('postType', 'campaign', {
                per_page: -1,
                status: 'publish',
            });
        }, []);

        const updateAttribute = (key, value) => {
            setAttributes({ [key]: value });
        };

        // Prepare campaign options for the dropdown
        const campaignOptions = campaigns ? [
            { label: __('Use Current Post (Auto-detect)', 'giftflowwp'), value: 0 },
            ...campaigns.map(campaign => ({
                label: campaign.title.rendered,
                value: campaign.id
            }))
        ] : [{ label: __('Loading campaigns...', 'giftflowwp'), value: 0 }];

        // Custom styles for better UI
        const panelStyles = {
            marginBottom: '24px',
        };

        const controlStyles = {
            marginBottom: '20px',
        };

        const labelStyles = {
            display: 'block',
            marginBottom: '8px',
            fontWeight: '600',
            fontSize: '13px',
            color: '#1e1e1e',
        };

        const helpTextStyles = {
            fontSize: '12px',
            color: '#757575',
            marginTop: '4px',
            lineHeight: '1.4',
        };

        const colorPickerContainerStyles = {
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
        };

        const colorPreviewStyles = {
            width: '32px',
            height: '32px',
            borderRadius: '6px',
            border: '2px solid #e2e8f0',
            boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)',
        };

        const rangeContainerStyles = {
            display: 'flex',
            flexDirection: 'column',
            gap: '8px',
        };

        const rangeValueStyles = {
            fontSize: '12px',
            color: '#666',
            textAlign: 'center',
            padding: '4px 8px',
            backgroundColor: '#f8f9fa',
            borderRadius: '4px',
            border: '1px solid #e2e8f0',
            display: 'inline-block',
            minWidth: '40px',
        };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Campaign Settings', 'giftflowwp')} initialOpen={true} style={panelStyles}>
                        <div style={controlStyles}>
                            <SelectControl
                                label={__('Select Campaign', 'giftflowwp')}
                                value={attributes.campaignId}
                                options={campaignOptions}
                                onChange={(value) => updateAttribute('campaignId', parseInt(value))}
                                help={__('Choose a specific campaign or leave as "Auto-detect" to use the current post.', 'giftflowwp')}
                            />
                        </div>
                    </PanelBody>

                    <PanelBody title={__('Button Settings', 'giftflowwp')} initialOpen={true} style={panelStyles}>
                        <div style={controlStyles}>
                            <TextControl
                                label={__('Button Text', 'giftflowwp')}
                                value={attributes.buttonText}
                                onChange={(value) => updateAttribute('buttonText', value)}
                                help={__('Customize the text displayed on the donation button.', 'giftflowwp')}
                            />
                        </div>
                        <div style={controlStyles}>
                            <ToggleControl
                                label={__('Full Width Button', 'giftflowwp')}
                                checked={attributes.fullWidth}
                                onChange={(value) => updateAttribute('fullWidth', value)}
                                help={__('Make the button span the full width of its container.', 'giftflowwp')}
                            />
                        </div>
                    </PanelBody>

                    <PanelBody title={__('Button Styling', 'giftflowwp')} initialOpen={false} style={panelStyles}>
                        <div style={controlStyles}>
                            <BaseControl
                                label={__('Background Color', 'giftflowwp')}
                                help={__('Choose the background color for your button.', 'giftflowwp')}
                            >
                                <div style={colorPickerContainerStyles}>
                                    <ColorPicker
                                        color={attributes.backgroundColor}
                                        onChange={(color) => updateAttribute('backgroundColor', color)}
                                        enableAlpha={false}
                                    />
                                    <div 
                                        style={{
                                            ...colorPreviewStyles,
                                            backgroundColor: attributes.backgroundColor
                                        }}
                                        title={attributes.backgroundColor}
                                    />
                                </div>
                            </BaseControl>
                        </div>

                        <div style={controlStyles}>
                            <BaseControl
                                label={__('Text Color', 'giftflowwp')}
                                help={__('Choose the text color for your button.', 'giftflowwp')}
                            >
                                <div style={colorPickerContainerStyles}>
                                    <ColorPicker
                                        color={attributes.textColor}
                                        onChange={(color) => updateAttribute('textColor', color)}
                                        enableAlpha={false}
                                    />
                                    <div 
                                        style={{
                                            ...colorPreviewStyles,
                                            backgroundColor: attributes.textColor
                                        }}
                                        title={attributes.textColor}
                                    />
                                </div>
                            </BaseControl>
                        </div>

                        <div style={controlStyles}>
                            <BaseControl
                                label={__('Border Radius', 'giftflowwp')}
                                help={__('Adjust the corner roundness of your button.', 'giftflowwp')}
                            >
                                <div style={rangeContainerStyles}>
                                    <input
                                        type="range"
                                        min="0"
                                        max="20"
                                        value={attributes.borderRadius}
                                        onChange={(e) => updateAttribute('borderRadius', parseInt(e.target.value))}
                                        style={{
                                            width: '100%',
                                            height: '6px',
                                            borderRadius: '3px',
                                            background: '#e2e8f0',
                                            outline: 'none',
                                            WebkitAppearance: 'none',
                                        }}
                                    />
                                    <div style={rangeValueStyles}>
                                        {attributes.borderRadius}px
                                    </div>
                                </div>
                            </BaseControl>
                        </div>
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <Disabled>
                        <ServerSideRender 
                            block="giftflowwp/donation-button" 
                            attributes={ attributes } />
                    </Disabled>
                </div>
            </>
        );
    },
});
