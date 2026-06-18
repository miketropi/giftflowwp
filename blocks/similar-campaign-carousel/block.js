import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, ColorPalette, BaseControl, TextControl, TextareaControl, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ShimmerBox, ShimmerBar, ensureShimmerStyles, useCampaignSelector } from '../_editor-utils';

registerBlockType('giftflow/similar-campaign-carousel', {
    apiVersion: 3,
    title: __('Similar Campaign Carousel', 'giftflow'),
    icon: 'slides',
    category: 'giftflow',
    attributes: {
        perPage: { type: 'number', default: 9 },
        columns: { type: 'integer', default: 3 },
        imageHeight: { type: 'integer', default: 240 },
        showProgress: { type: 'boolean', default: true },
        showMeta: { type: 'boolean', default: true },
        autoplay: { type: 'boolean', default: false },
        autoplayDelay: { type: 'number', default: 4000 },
        loop: { type: 'boolean', default: true },
        progressColor: { type: 'string', default: '' },
        eyebrow: { type: 'string', default: '' },
        heading: { type: 'string', default: 'Similar Campaigns' },
        description: { type: 'string', default: '' },
        headerAlign: { type: 'string', default: 'center' },
        excludeCurrent: { type: 'boolean', default: true },
        sourceCampaignId: { type: 'number', default: 0 },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const a = attributes;
        const fillColor = a.progressColor || '#2563eb';
        const imgH = a.imageHeight || 240;
        const blockProps = useBlockProps({ className: 'giftflow-similar-carousel', style: { '--gf-carousel-img-height': imgH + 'px' } });
        ensureShimmerStyles();

        const { CampaignSelector } = useCampaignSelector({
            defaultLabel: __('Use current page', 'giftflow'),
            defaultValue: 0,
        });

        const lb = { marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Query', 'giftflow')} initialOpen={true}>
                        <CampaignSelector
                            value={a.sourceCampaignId}
                            onChange={(v) => setAttributes({ sourceCampaignId: v })}
                            help={__('Select a reference campaign or leave empty to use the current page.', 'giftflow')}
                        />
                        <RangeControl label={__('Total campaigns', 'giftflow')} value={a.perPage} onChange={v => setAttributes({ perPage: v || 9 })} min={1} max={24} help={__('Campaigns are matched by shared categories with the reference campaign. Falls back to recent campaigns if no categories match.', 'giftflow')} />
                    </PanelBody>
                    <PanelBody title={__('Header', 'giftflow')} initialOpen={false}>
                        <TextControl label={__('Eyebrow', 'giftflow')} value={a.eyebrow} onChange={v => setAttributes({ eyebrow: v })} help={__('Small badge text above heading.', 'giftflow')} />
                        <TextControl label={__('Heading', 'giftflow')} value={a.heading} onChange={v => setAttributes({ heading: v })} />
                        <TextareaControl label={__('Description', 'giftflow')} value={a.description} onChange={v => setAttributes({ description: v })} />
                        <div style={{ marginTop: 12 }}>
                            <div style={lb}>{__('Alignment', 'giftflow')}</div>
                            <ToggleGroupControl value={a.headerAlign || 'center'} onChange={v => setAttributes({ headerAlign: v })} isBlock __nextHasNoMarginBottom>
                                <ToggleGroupControlOption value="left" label={__('Left', 'giftflow')} />
                                <ToggleGroupControlOption value="center" label={__('Center', 'giftflow')} />
                                <ToggleGroupControlOption value="right" label={__('Right', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                    </PanelBody>
                    <PanelBody title={__('Layout', 'giftflow')} initialOpen={false}>
                        <RangeControl label={__('Columns (desktop)', 'giftflow')} value={a.columns} onChange={v => setAttributes({ columns: v })} min={1} max={5} />
                        <RangeControl label={__('Image height', 'giftflow')} value={imgH} onChange={v => setAttributes({ imageHeight: v })} min={150} max={400} step={10} />
                    </PanelBody>
                    <PanelBody title={__('Content', 'giftflow')} initialOpen={false}>
                        <ToggleControl label={__('Show progress', 'giftflow')} checked={a.showProgress} onChange={v => setAttributes({ showProgress: v })} />
                        <ToggleControl label={__('Show meta', 'giftflow')} checked={a.showMeta} onChange={v => setAttributes({ showMeta: v })} />
                    </PanelBody>
                    <PanelBody title={__('Playback', 'giftflow')} initialOpen={false}>
                        <ToggleControl label={__('Autoplay', 'giftflow')} checked={a.autoplay} onChange={v => setAttributes({ autoplay: v })} />
                        {a.autoplay && <RangeControl label={__('Delay (ms)', 'giftflow')} value={a.autoplayDelay} onChange={v => setAttributes({ autoplayDelay: v })} min={1000} max={10000} step={500} />}
                        <ToggleControl label={__('Loop', 'giftflow')} checked={a.loop} onChange={v => setAttributes({ loop: v })} />
                    </PanelBody>
                    <PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
                        <BaseControl label={__('Accent color', 'giftflow')}>
                            <ColorPalette value={a.progressColor} onChange={v => setAttributes({ progressColor: v || '' })} disableCustomColors={false} clearable={true} />
                        </BaseControl>
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {(a.eyebrow || a.heading || a.description) && (
                        <div style={{ textAlign: a.headerAlign || 'center', marginBottom: 32 }}>
                            {a.eyebrow && <span style={{ display: 'inline-block', padding: '4px 12px', background: fillColor, color: '#fff', borderRadius: 99, fontSize: 12, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.04em', marginBottom: 12 }}>{a.eyebrow}</span>}
                            {a.heading && <h2 style={{ margin: '0 0 8px', fontSize: 28, fontWeight: 700, color: '#111827' }}>{a.heading}</h2>}
                            {a.description && <p style={{ margin: 0, maxWidth: a.headerAlign === 'center' ? 560 : 'none', marginLeft: a.headerAlign === 'left' ? 0 : 'auto', marginRight: a.headerAlign === 'right' ? 0 : 'auto', fontSize: 15, lineHeight: 1.6, color: '#6b7280' }}>{a.description}</p>}
                        </div>
                    )}
                    <div style={{ display: 'flex', gap: 20, overflow: 'hidden', paddingBottom: 40 }}>
                        {[0, 1, 2].map(i => (
                            <div key={i} style={{ flex: '0 0 calc(33.333% - 14px)', borderRadius: 16, overflow: 'hidden', border: '1px solid #e5e7eb', background: '#fff' }}>
                                <div style={{ position: 'relative' }}>
                                    <ShimmerBox height={imgH} style={{ borderRadius: 0 }} />
                                    <div style={{ position: 'absolute', top: 12, left: 12, padding: '4px 10px', background: 'rgba(255,255,255,0.9)', borderRadius: 6, fontSize: 11, fontWeight: 600, color: fillColor }}>{__('Category', 'giftflow')}</div>
                                </div>
                                <div style={{ padding: '18px 20px 20px' }}>
                                    <ShimmerBar height={16} width="80%" style={{ marginBottom: 8 }} />
                                    <div style={{ marginBottom: 12 }}><ShimmerBar height={12} width="90%" /><ShimmerBar height={12} width="60%" style={{ marginTop: 5 }} /></div>
                                    {a.showProgress && <div style={{ marginBottom: 10 }}><div style={{ height: 8, borderRadius: 99, background: '#f3f4f6', marginBottom: 6 }}><div style={{ height: '100%', width: `${30 + i * 30}%`, borderRadius: 99, background: fillColor }} /></div><ShimmerBar height={12} width={40} /></div>}
                                    {a.showMeta && <div style={{ display: 'flex', gap: 8, marginBottom: 8 }}><ShimmerBar height={12} width={60} /><ShimmerBar height={12} width={50} /></div>}
                                    <div style={{ display: 'flex', justifyContent: 'center', padding: '10px 16px', marginTop: 8, borderRadius: 10, background: fillColor, color: '#fff', fontSize: 13, fontWeight: 600 }}>{__('Read more', 'giftflow')} →</div>
                                </div>
                            </div>
                        ))}
                    </div>
                    <div style={{ display: 'flex', justifyContent: 'center', gap: 8, marginTop: 16 }}>
                        <div style={{ width: 24, height: 8, borderRadius: 4, background: fillColor }} />
                        <div style={{ width: 8, height: 8, borderRadius: '50%', background: '#d1d5db' }} />
                        <div style={{ width: 8, height: 8, borderRadius: '50%', background: '#d1d5db' }} />
                    </div>
                </div>
            </>
        );
    },
});
