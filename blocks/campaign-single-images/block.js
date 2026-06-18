import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ShimmerBox, ensureShimmerStyles, useCampaignSelector } from '../_editor-utils';

registerBlockType('giftflow/campaign-single-images', {
    apiVersion: 3,
    title: __('Campaign Images', 'giftflow'),
    icon: 'format-gallery',
    category: 'giftflow',
    usesContext: ['postId', 'postType'],
    attributes: {
        campaignId: { type: 'number', default: 0 },
    },
    edit: ({ attributes, setAttributes }) => {
        const { campaignId } = attributes;
        const { CampaignSelector } = useCampaignSelector({
            defaultLabel: __('Use current page', 'giftflow'),
            defaultValue: 0,
        });
        const blockProps = useBlockProps({ className: 'giftflow-campaign-images' });
        ensureShimmerStyles();

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Campaign', 'giftflow')} initialOpen={true}>
                        <CampaignSelector
                            value={campaignId}
                            onChange={(v) => setAttributes({ campaignId: v })}
                            help={__('Select a specific campaign or leave empty to use the current page.', 'giftflow')}
                        />
                    </PanelBody>
                    <PanelBody title={__('About', 'giftflow')} initialOpen={false}>
                        <p style={{ color: '#757575', fontSize: 13 }}>
                            {__('Displays the featured image and gallery for a campaign. Click thumbnails to swap the main image, or click the main image to open the fullscreen lightbox viewer.', 'giftflow')}
                        </p>
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    <div style={{ position: 'relative', borderRadius: 12, overflow: 'hidden', marginBottom: '0.75rem', aspectRatio: '4 / 3', maxHeight: 480, background: '#f3f4f6' }}>
                        <ShimmerBox height="100%" style={{ borderRadius: 0, position: 'absolute', inset: 0 }} />
                        <div style={{ position: 'absolute', inset: 0, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                            <div style={{ width: 48, height: 48, borderRadius: '50%', background: 'rgba(0,0,0,0.35)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff' }}>
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6M8 11h6"/></svg>
                            </div>
                        </div>
                        <div style={{ position: 'absolute', bottom: 12, right: 12, background: 'rgba(0,0,0,0.5)', color: '#fff', padding: '3px 10px', borderRadius: 999, fontSize: 12, fontWeight: 500 }}>1 / 6</div>
                    </div>
                    <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
                        {[0, 1, 2, 3].map(i => (
                            <button key={i} style={{ flex: '0 0 calc(25% - 0.375rem)', border: i === 0 ? '2px solid #3b82f6' : '2px solid transparent', borderRadius: 8, overflow: 'hidden', padding: 0, background: 'none', cursor: 'pointer' }}>
                                <div style={{ aspectRatio: '1 / 1' }}>
                                    <ShimmerBox height="100%" style={{ borderRadius: 6 }} />
                                </div>
                            </button>
                        ))}
                        <button style={{ flex: '0 0 calc(25% - 0.375rem)', border: '2px solid transparent', borderRadius: 8, overflow: 'hidden', padding: 0, background: '#f3f4f6', cursor: 'pointer', position: 'relative' }}>
                            <div style={{ aspectRatio: '1 / 1' }}>
                                <ShimmerBox height="100%" style={{ borderRadius: 6 }} />
                            </div>
                            <div style={{ position: 'absolute', inset: 0, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 16, fontWeight: 600, color: '#555' }}>+2</div>
                        </button>
                    </div>
                </div>
            </>
        );
    },
});
