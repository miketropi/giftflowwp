import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, ToggleControl, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption, ColorPalette, BaseControl, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
const { useSelect } = wp.data;

const ICONS = {
    none: null,
    heart: (s = 18) => <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" /></svg>,
    sparkle: (s = 18) => <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" /></svg>,
    gift: (s = 18) => <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="8" width="18" height="4" rx="1" /><path d="M12 8v13" /><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" /><path d="M7.5 8a2.5 2.5 0 0 1 0-5C4.5 3 5 6 12 8" /><path d="M16.5 8a2.5 2.5 0 0 0 0-5C19.5 3 19 6 12 8" /></svg>,
    arrow: (s = 18) => <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>,
    'ribbon-heart': (s = 18) => <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" /></svg>,
};

function resolveVars(attr) {
    const isOutline = attr.buttonStyle === 'outline';
    const isSoft = attr.buttonStyle === 'soft';
    const isPill = attr.buttonStyle === 'pill';
    const bg = attr.backgroundColor || '#1e1e1e';
    const fg = attr.textColor || '#ffffff';

    const vars = {
        '--gf-btn-bg': isOutline || isSoft ? 'transparent' : bg,
        '--gf-btn-fg': isOutline || isSoft ? bg : fg,
        '--gf-btn-border': isOutline ? `2px solid ${bg}` : isSoft ? '2px solid transparent' : 'none',
        '--gf-btn-bg-image': isSoft ? `linear-gradient(${bg}0d, ${bg}14)` : 'none',
        '--gf-btn-radius': isPill ? '999px' : `${attr.borderRadius || 8}px`,
        '--gf-btn-padding': attr.buttonPadding || '14px 32px',
        transition: 'all 0.25s cubic-bezier(0.4, 0, 0.2, 1)',
        position: 'relative',
        overflow: 'hidden',
    };
    if (attr.hoverBgColor) vars['--gf-hover-bg'] = attr.hoverBgColor;
    if (attr.hoverTextColor) vars['--gf-hover-fg'] = attr.hoverTextColor;

    return vars;
}

registerBlockType('giftflow/donation-button', {
    apiVersion: 3,
    title: __('Donation Button', 'giftflow'),
    icon: 'heart',
    category: 'giftflow',
    attributes: {
        campaignId: { type: 'number', default: 0 },
        buttonText: { type: 'string', default: __('Donate Now', 'giftflow') },
        buttonStyle: { type: 'string', default: 'filled' },
        backgroundColor: { type: 'string', default: '#1e1e1e' },
        textColor: { type: 'string', default: '#ffffff' },
        hoverBgColor: { type: 'string', default: '' },
        hoverTextColor: { type: 'string', default: '' },
        hoverEffect: { type: 'string', default: 'lift' },
        borderRadius: { type: 'number', default: 8 },
        buttonPadding: { type: 'string', default: '14px 32px' },
        icon: { type: 'string', default: 'none' },
        iconPosition: { type: 'string', default: 'before' },
        fullWidth: { type: 'boolean', default: false },
    },

    edit: (props) => {
        const { attributes, setAttributes } = props;
        const a = attributes;

        const blockProps = useBlockProps({ className: 'giftflow-donation-button' });

        const campaigns = useSelect((s) => s('core').getEntityRecords('postType', 'campaign', { per_page: -1, status: 'publish' }), []);
        const campaignOptions = campaigns
            ? [{ label: __('Use Current Post', 'giftflow'), value: 0 }, ...campaigns.map(c => ({ label: c.title.rendered, value: c.id }))]
            : [{ label: __('Loading…', 'giftflow'), value: 0 }];
        const selected = campaigns && a.campaignId > 0 ? campaigns.find(c => c.id === a.campaignId) : null;

        const vars = resolveVars(a);
        const IconCmp = ICONS[a.icon] || null;
        const iconBefore = IconCmp && a.iconPosition === 'before';
        const iconAfter = IconCmp && a.iconPosition === 'after';
        const isDisabled = a.campaignId === 0;

        const btnClasses = [
            'giftflow-donation-button__btn',
            a.fullWidth ? 'giftflow-donation-button__btn--full-width' : '',
            isDisabled ? 'giftflow-donation-button__btn--disabled' : '',
            (a.hoverBgColor || a.hoverTextColor) ? 'giftflow-donation-button__btn--has-hover-color' : '',
            a.hoverEffect && a.hoverEffect !== 'none' ? 'giftflow-donation-button__btn--hover-' + a.hoverEffect : '',
        ].filter(Boolean).join(' ');

        const sectionStyle = { marginBottom: 20 };
        const labelStyle = { marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' };

        return (
            <>
                <InspectorControls group="styles">
                    {null}
                </InspectorControls>

                <InspectorControls>
                    <PanelBody title={__('Campaign', 'giftflow')}>
                        <SelectControl label={__('Target campaign', 'giftflow')} value={a.campaignId} options={campaignOptions} onChange={v => setAttributes({ campaignId: parseInt(v) })} __nextHasNoMarginBottom />
                    </PanelBody>

                    <PanelBody title={__('Content', 'giftflow')}>
                        <TextControl label={__('Button text', 'giftflow')} value={a.buttonText} onChange={v => setAttributes({ buttonText: v })} __nextHasNoMarginBottom />
                        <div style={sectionStyle}>
                            <div style={labelStyle}>{__('Icon', 'giftflow')}</div>
                            <ToggleGroupControl value={a.icon} onChange={v => setAttributes({ icon: v })} isBlock __nextHasNoMarginBottom>
                                <ToggleGroupControlOption value="none" label={__('None', 'giftflow')} />
                                <ToggleGroupControlOption value="heart" label="❤️" />
                                <ToggleGroupControlOption value="sparkle" label="✨" />
                                <ToggleGroupControlOption value="gift" label="🎁" />
                                <ToggleGroupControlOption value="arrow" label="→" />
                                <ToggleGroupControlOption value="ribbon-heart" label="💝" />
                            </ToggleGroupControl>
                        </div>
                        {a.icon !== 'none' && (
                            <div style={sectionStyle}>
                                <div style={labelStyle}>{__('Icon position', 'giftflow')}</div>
                                <ToggleGroupControl value={a.iconPosition} onChange={v => setAttributes({ iconPosition: v })} isBlock __nextHasNoMarginBottom>
                                    <ToggleGroupControlOption value="before" label={__('Before', 'giftflow')} />
                                    <ToggleGroupControlOption value="after" label={__('After', 'giftflow')} />
                                </ToggleGroupControl>
                            </div>
                        )}
                        <ToggleControl label={__('Full width', 'giftflow')} checked={a.fullWidth} onChange={v => setAttributes({ fullWidth: v })} __nextHasNoMarginBottom />
                    </PanelBody>

                    <PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
                        <div style={sectionStyle}>
                            <BaseControl label={__('Background color', 'giftflow')}>
                                <ColorPalette value={a.backgroundColor} onChange={v => setAttributes({ backgroundColor: v || '#1e1e1e' })} disableCustomColors={false} clearable={false} />
                            </BaseControl>
                        </div>
                        <div style={sectionStyle}>
                            <BaseControl label={__('Text color', 'giftflow')}>
                                <ColorPalette value={a.textColor} onChange={v => setAttributes({ textColor: v || '#ffffff' })} disableCustomColors={false} clearable={false} />
                            </BaseControl>
                        </div>
                        <div style={sectionStyle}>
                            <div style={labelStyle}>{__('Padding', 'giftflow')}</div>
                            <ToggleGroupControl value={a.buttonPadding || ''} onChange={v => setAttributes({ buttonPadding: v || '' })} isBlock __nextHasNoMarginBottom>
                                <ToggleGroupControlOption value="" label={__('None', 'giftflow')} />
                                <ToggleGroupControlOption value="8px 16px" label={__('S', 'giftflow')} />
                                <ToggleGroupControlOption value="14px 32px" label={__('M', 'giftflow')} />
                                <ToggleGroupControlOption value="18px 40px" label={__('L', 'giftflow')} />
                                <ToggleGroupControlOption value="22px 48px" label={__('XL', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                        <div style={{ marginBottom: 0 }}>
                            <RangeControl
                                label={__('Border radius', 'giftflow')}
                                value={a.borderRadius || 8}
                                onChange={v => setAttributes({ borderRadius: v })}
                                min={0}
                                max={40}
                                allowReset={true}
                                resetFallbackValue={8}
                            />
                        </div>
                        <div style={sectionStyle}>
                            <div style={labelStyle}>{__('Hover effect', 'giftflow')}</div>
                            <ToggleGroupControl value={a.hoverEffect} onChange={v => setAttributes({ hoverEffect: v })} isBlock __nextHasNoMarginBottom>
                                <ToggleGroupControlOption value="lift" label={__('Lift', 'giftflow')} />
                                <ToggleGroupControlOption value="glow" label={__('Glow', 'giftflow')} />
                                <ToggleGroupControlOption value="scale" label={__('Scale', 'giftflow')} />
                                <ToggleGroupControlOption value="none" label={__('None', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                        <div style={{ ...sectionStyle, marginBottom: 8 }}>
                            <BaseControl label={__('Hover background color', 'giftflow')}>
                                <ColorPalette value={a.hoverBgColor} onChange={v => setAttributes({ hoverBgColor: v || '' })} disableCustomColors={false} clearable={true} />
                            </BaseControl>
                        </div>
                        <div style={sectionStyle}>
                            <BaseControl label={__('Hover text color', 'giftflow')}>
                                <ColorPalette value={a.hoverTextColor} onChange={v => setAttributes({ hoverTextColor: v || '' })} disableCustomColors={false} clearable={true} />
                            </BaseControl>
                        </div>
                        <div style={sectionStyle}>
                            <div style={labelStyle}>{__('Padding', 'giftflow')}</div>
                            <ToggleGroupControl
                                value={a.buttonPadding || ''}
                                onChange={v => setAttributes({ buttonPadding: v || '' })}
                                isBlock
                                __nextHasNoMarginBottom
                            >
                                <ToggleGroupControlOption value="" label={__('None', 'giftflow')} />
                                <ToggleGroupControlOption value="8px 16px" label={__('S', 'giftflow')} />
                                <ToggleGroupControlOption value="14px 32px" label={__('M', 'giftflow')} />
                                <ToggleGroupControlOption value="18px 40px" label={__('L', 'giftflow')} />
                                <ToggleGroupControlOption value="22px 48px" label={__('XL', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    {selected && (
                        <div style={{ fontSize: 11.5, fontWeight: 500, color: '#3b82f6', marginBottom: 8, textAlign: 'center', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 5 }}>
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><polyline points="20 6 9 17 4 12" /></svg>
                            {selected.title.rendered}
                        </div>
                    )}

                    <div style={{ textAlign: a.fullWidth ? 'stretch' : 'center' }}>
                        <span
                            role="button"
                            className={btnClasses}
                            style={{
                                ...vars,
                                pointerEvents: isDisabled ? 'none' : undefined,
                                opacity: isDisabled ? 0.45 : undefined,
                            }}
                            data-campaign-id={a.campaignId}
                        >
                            {iconBefore && IconCmp(16)}
                            <span className="giftflow-donation-button__label">{a.buttonText}</span>
                            {iconAfter && IconCmp(16)}
                        </span>
                    </div>

                    {isDisabled && (
                        <div style={{ textAlign: 'center', marginTop: 10, fontSize: 11, color: '#9ca3af', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 5 }}>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="10" /><path d="M12 16v-4" /><path d="M12 8h.01" /></svg>
                            {__('Pick a campaign target in the sidebar', 'giftflow')}
                        </div>
                    )}
                </div>
            </>
        );
    },
});
