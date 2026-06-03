import '../../assets/css/block-campaign-status-bar.scss';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ColorPalette, BaseControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ensureShimmerStyles } from '../_editor-utils';
const { useSelect } = wp.data;

registerBlockType('giftflow/campaign-status-bar', {
    apiVersion: 3,
    title: __('Campaign Status Bar', 'giftflow'),
    icon: 'chart-bar',
    category: 'giftflow',
    attributes: {
        campaignId: { type: 'number', default: 0 },
        progressColor: { type: 'string', default: '' },
    },
    usesContext: ['postId', 'postType'],
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps({ className: 'giftflow-campaign-status-bar' });
        ensureShimmerStyles();

        const campaigns = useSelect((s) => s('core').getEntityRecords('postType', 'campaign', { per_page: -1, status: 'publish' }), []);
        const campaignOptions = campaigns
            ? [{ label: __('Auto-detect from current post', 'giftflow'), value: 0 }, ...campaigns.map(c => ({ label: c.title.rendered, value: c.id }))]
            : [{ label: __('Loading...', 'giftflow'), value: 0 }];
        const selected = campaigns && attributes.campaignId > 0 ? campaigns.find(c => c.id === attributes.campaignId) : null;
        const fillColor = attributes.progressColor || '';

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Campaign Settings', 'giftflow')} initialOpen={true}>
                        <SelectControl label={__('Campaign', 'giftflow')} value={attributes.campaignId || 0} options={campaignOptions} onChange={v => setAttributes({ campaignId: parseInt(v) })} help={__('Select a campaign or use auto-detect.', 'giftflow')} />
                    </PanelBody>
                    <PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
                        <BaseControl label={__('Progress bar color', 'giftflow')}>
                            <ColorPalette value={attributes.progressColor} onChange={v => setAttributes({ progressColor: v || '' })} disableCustomColors={false} clearable={true} />
                        </BaseControl>
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {selected && (
                        <div style={{ fontSize: 11, fontWeight: 500, color: '#3b82f6', marginBottom: 10, display: 'flex', alignItems: 'center', gap: 6 }}>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><polyline points="20 6 9 17 4 12" /></svg>
                            {selected.title.rendered}
                        </div>
                    )}
                    <div className="giftflow-campaign-status-bar__progress">
                        <div className="giftflow-campaign-status-bar__progress-fill" style={{ width: '42%', ...(fillColor ? { backgroundColor: fillColor } : {}) }}></div>
                    </div>
                    <div className="giftflow-campaign-status-bar__stats">
                        <span className="giftflow-campaign-status-bar__raised">$4,200 {__('raised of', 'giftflow')} $10,000</span>
                        <span className="giftflow-campaign-status-bar__donors">12 {__('donors', 'giftflow')}</span>
                        <span className="giftflow-campaign-status-bar__days">18 {__('days left', 'giftflow')}</span>
                    </div>
                </div>
            </>
        );
    },
});
