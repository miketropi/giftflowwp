import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, InnerBlocks, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl, Button, ColorPalette, BaseControl, RangeControl, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ShimmerBox, ensureShimmerStyles, useCampaignSelector } from '../_editor-utils';

const BLOCK_TEMPLATE = [
    ['core/group', { style: { spacing: { blockGap: '0.5rem' } }, layout: { type: 'constrained' } }, [
        ['core/heading', { level: 2, placeholder: __('Campaign title', 'giftflow'), style: { typography: { fontSize: '1.75rem', lineHeight: '1.25' } }, className: 'giftflow-featured-campaign__heading' }],
        ['core/paragraph', { placeholder: __('Campaign description…', 'giftflow'), style: { color: { text: '#6b7280' }, typography: { fontSize: '0.95rem', lineHeight: '1.55' } } }],
    ]],
    ['core/heading', { level: 4, content: __('Sponsors:', 'giftflow'), style: { typography: { fontSize: '1.1rem', lineHeight: '1.5' } }, className: 'giftflow-featured-campaign__sponsors-heading' }],
    ['giftflow/sponsor-logos', {}],
    ['core/spacer', { height: '10px' }],
    ['giftflow/campaign-status-bar', {}],
    ['giftflow/donation-button', { buttonText: __('Donate Now', 'giftflow') }],
];

registerBlockType('giftflow/featured-campaign', {
    apiVersion: 3,
    title: __('Featured Campaign', 'giftflow'),
    icon: 'star-filled',
    category: 'giftflow',
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const a = attributes;
        const accent = a.accentColor || '#2563eb';
        const imgH = a.imageHeight || 400;
        const radius = a.borderRadius || 0;
        const mediaType = a.mediaType || 'image';
        const videoUrl = a.videoUrl || '';
        const videoId = a.videoId || 0;
        const blockStyle = a.style || {};
        const borderStyle = blockStyle.border || {};
        const borderRadius = borderStyle.radius || (radius > 0 ? radius : undefined);

        const layoutClass = a.imageOnLeft
            ? 'giftflow-featured-campaign--image-left'
            : 'giftflow-featured-campaign--image-right';

        const blockProps = useBlockProps({
            className: `giftflow-featured-campaign ${layoutClass}`,
            style: {
                '--giftflow--featured-accent': accent,
                ...(radius > 0 ? { borderRadius: radius } : {}),
            },
        });
        ensureShimmerStyles();

        const { CampaignSelector } = useCampaignSelector({ defaultLabel: __('Select a campaign…', 'giftflow') });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Campaign', 'giftflow')} initialOpen={true}>
                        <CampaignSelector
                            value={a.campaignId || 0}
                            onChange={(v) => setAttributes({ campaignId: v })}
                            help={__('Inner blocks receive this campaign as context.', 'giftflow')}
                        />
                    </PanelBody>
                    <PanelBody title={__('Image', 'giftflow')} initialOpen={false}>
                        <div style={{ marginBottom: 16 }}>
                            <div style={{ marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' }}>{__('Media type', 'giftflow')}</div>
                            <ToggleGroupControl value={mediaType} onChange={v => setAttributes({ mediaType: v })} isBlock __nextHasNoMarginBottom>
                                <ToggleGroupControlOption value="image" label={__('Image', 'giftflow')} />
                                <ToggleGroupControlOption value="video" label={__('Video', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                        {mediaType === 'video' && (
                            <>
                                <MediaUpload
                                    onSelect={(media) => setAttributes({ videoId: media.id, videoUrl: media.url })}
                                    allowedTypes={['video']}
                                    value={videoId}
                                    render={({ open }) => (
                                        <div style={{ marginBottom: 16 }}>
                                            <div style={{ marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' }}>{__('Select video', 'giftflow')}</div>
                                            <Button
                                                variant="secondary"
                                                onClick={open}
                                                style={{ width: '100%', justifyContent: 'center' }}
                                            >
                                                {videoId ? __('Replace video', 'giftflow') : __('Choose from Media Library', 'giftflow')}
                                            </Button>
                                            {videoId && (
                                                <p style={{ margin: '6px 0 0', fontSize: 11, color: '#6b7280' }}>
                                                    {videoUrl ? videoUrl.split('/').pop() : __('Video selected', 'giftflow')}
                                                </p>
                                            )}
                                        </div>
                                    )}
                                />
                                <TextControl
                                    label={__('Or paste external URL', 'giftflow')}
                                    value={videoUrl}
                                    onChange={v => setAttributes({ videoUrl: v, videoId: 0 })}
                                    help={__('YouTube, Vimeo, or direct MP4 URL.', 'giftflow')}
                                />
                                <TextControl
                                    label={__('Poster image URL', 'giftflow')}
                                    value={a.videoPoster || ''}
                                    onChange={v => setAttributes({ videoPoster: v })}
                                    help={__('Optional. Preview image for self-hosted video.', 'giftflow')}
                                />
                            </>
                        )}
                        <RangeControl
                            label={__('Image height (px)', 'giftflow')}
                            value={imgH}
                            onChange={v => setAttributes({ imageHeight: v })}
                            min={200}
                            max={600}
                            step={10}
                        />
                        <ToggleControl
                            label={__('Image on left', 'giftflow')}
                            checked={a.imageOnLeft}
                            onChange={v => setAttributes({ imageOnLeft: v })}
                            help={__('When off, image appears on the right.', 'giftflow')}
                        />
                        <ToggleControl
                            label={__('Show featured badge', 'giftflow')}
                            checked={a.showFeaturedBadge}
                            onChange={v => setAttributes({ showFeaturedBadge: v })}
                        />
                    </PanelBody>
                    <PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
                        <RangeControl
                            label={__('Border radius', 'giftflow')}
                            value={radius}
                            onChange={v => setAttributes({ borderRadius: v })}
                            min={0}
                            max={40}
                            allowReset={true}
                            resetFallbackValue={0}
                        />
                        <BaseControl label={__('Accent color', 'giftflow')}>
                            <ColorPalette
                                value={a.accentColor}
                                onChange={v => setAttributes({ accentColor: v || '' })}
                                disableCustomColors={false}
                                clearable={true}
                            />
                        </BaseControl>
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <div
                        className="giftflow-featured-campaign__image-area"
                        style={{ minHeight: imgH, ...(borderRadius ? { borderRadius } : {}) }}
                    >
                        {mediaType === 'video' ? (
                            <div style={{ position: 'absolute', inset: 0, background: '#111', display: 'flex', alignItems: 'center', justifyContent: 'center', borderRadius: 0 }}>
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="rgba(255,255,255,0.3)" stroke="none"><circle cx="12" cy="12" r="12" fill="rgba(255,255,255,0.15)"/><polygon points="10,8 17,12 10,16" fill="white"/></svg>
                            </div>
                        ) : (
                            <ShimmerBox height="100%" style={{ position: 'absolute', inset: 0, borderRadius: 0 }} />
                        )}
                        {a.showFeaturedBadge && (
                            <span className="giftflow-featured-campaign__badge" style={{ background: accent, color: '#fff' }}>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
                                {__('Featured', 'giftflow')}
                            </span>
                        )}
                    </div>
                    <div className="giftflow-featured-campaign__content-area">
                        <InnerBlocks
                            template={BLOCK_TEMPLATE}
                            templateLock={false}
                        />
                    </div>
                </div>
            </>
        );
    },
    save: () => <InnerBlocks.Content />,
});
