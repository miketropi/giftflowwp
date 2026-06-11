import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaPlaceholder } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Button, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ShimmerBox, ensureShimmerStyles } from '../_editor-utils';

registerBlockType('giftflow/sponsor-logos', {
    apiVersion: 3,
    title: __('Sponsor Logos', 'giftflow'),
    icon: 'slides',
    category: 'giftflow',
    attributes: {
        logos: { type: 'array', default: [] },
        scrollSpeed: { type: 'number', default: 20 },
        gap: { type: 'number', default: 48 },
        logoHeight: { type: 'number', default: 60 },
        direction: { type: 'string', default: 'left' },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const a = attributes;
        const logos = a.logos || [];
        const speed = a.scrollSpeed || 20;
        const gap = a.gap || 48;
        const height = a.logoHeight || 60;
        const dir = a.direction || 'left';

        const dirClass = dir === 'right' ? 'giftflow-sponsor-logos--dir-right' : '';

        const blockProps = useBlockProps({
            className: `giftflow-sponsor-logos ${dirClass}`,
            style: {
                '--gf-sponsor-speed': `${speed}s`,
                '--gf-sponsor-gap': `${gap}px`,
                '--gf-sponsor-height': `${height}px`,
            },
        });
        ensureShimmerStyles();

        const lb = { marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' };

        const addLogos = (media) => {
            const newLogos = media.map((m) => ({
                id: m.id,
                url: m.url,
                alt: m.alt || m.title || '',
            }));
            setAttributes({ logos: [...logos, ...newLogos] });
        };

        const removeLogo = (id) => {
            setAttributes({ logos: logos.filter((l) => l.id !== id) });
        };

        const placeholderLogos = logos.length > 0 ? logos : [
            { id: 's1', url: '', alt: 'Sponsor 1' },
            { id: 's2', url: '', alt: 'Sponsor 2' },
            { id: 's3', url: '', alt: 'Sponsor 3' },
            { id: 's4', url: '', alt: 'Sponsor 4' },
            { id: 's5', url: '', alt: 'Sponsor 5' },
        ];

        const duplicated = [...placeholderLogos, ...placeholderLogos];

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Logos', 'giftflow')} initialOpen={true}>
                        {logos.length > 0 && (
                            <div style={{ maxHeight: 260, overflowY: 'auto', marginBottom: 12 }}>
                                {logos.map((logo) => (
                                    <div key={logo.id} style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 8, padding: 6, background: '#f9fafb', borderRadius: 6 }}>
                                        <div style={{ width: 36, height: 36, borderRadius: 4, overflow: 'hidden', flexShrink: 0, background: '#e5e7eb' }}>
                                            {logo.url ? <img src={logo.url} alt="" style={{ width: '100%', height: '100%', objectFit: 'contain' }} /> : null}
                                        </div>
                                        <span style={{ flex: 1, fontSize: 12, whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{logo.alt || __('Untitled', 'giftflow')}</span>
                                        <Button
                                            icon="no-alt"
                                            isSmall
                                            isDestructive
                                            label={__('Remove', 'giftflow')}
                                            onClick={() => removeLogo(logo.id)}
                                        />
                                    </div>
                                ))}
                            </div>
                        )}
                        <MediaPlaceholder
                            labels={{ title: '', instructions: __('Drag images here, or click to select sponsor logos.', 'giftflow') }}
                            onSelect={addLogos}
                            accept="image/*"
                            allowedTypes={['image']}
                            multiple
                            addToGallery={false}
                        />
                    </PanelBody>
                    <PanelBody title={__('Scrolling', 'giftflow')} initialOpen={false}>
                        <RangeControl
                            label={__('Speed (seconds)', 'giftflow')}
                            value={speed}
                            onChange={(v) => setAttributes({ scrollSpeed: v })}
                            min={5}
                            max={60}
                            step={1}
                            help={__('Lower = faster scroll.', 'giftflow')}
                        />
                        <div style={{ marginBottom: 16 }}>
                            <div style={lb}>{__('Direction', 'giftflow')}</div>
                            <ToggleGroupControl value={dir} onChange={(v) => setAttributes({ direction: v })} isBlock __nextHasNoMarginBottom>
                                <ToggleGroupControlOption value="left" label={__('Left', 'giftflow')} />
                                <ToggleGroupControlOption value="right" label={__('Right', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                    </PanelBody>
                    <PanelBody title={__('Layout', 'giftflow')} initialOpen={false}>
                        <RangeControl
                            label={__('Spacing (px)', 'giftflow')}
                            value={gap}
                            onChange={(v) => setAttributes({ gap: v })}
                            min={8}
                            max={120}
                            step={4}
                        />
                        <RangeControl
                            label={__('Logo height (px)', 'giftflow')}
                            value={height}
                            onChange={(v) => setAttributes({ logoHeight: v })}
                            min={24}
                            max={160}
                            step={4}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <div className="giftflow-sponsor-logos__track">
                        {duplicated.map((logo, i) => (
                            <div key={`${logo.id}-${i}`} className="giftflow-sponsor-logos__item" aria-hidden={i >= placeholderLogos.length}>
                                {logo.url ? (
                                    <img src={logo.url} alt={logo.alt} />
                                ) : (
                                    <div style={{ height, width: height * 2, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                        <ShimmerBox height={height * 0.7} width={height * 1.8} />
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                </div>
            </>
        );
    },
    save: () => null,
});
