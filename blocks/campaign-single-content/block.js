import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption, ColorPalette, BaseControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ShimmerBox, ShimmerBar, ensureShimmerStyles } from '../_editor-utils';

registerBlockType('giftflow/campaign-single-content', {
    apiVersion: 3,
    title: __('Campaign Content', 'giftflow'),
    icon: 'media-document',
    category: 'giftflow',
    usesContext: ['postId', 'postType'],
    attributes: {
        tabStyle: { type: 'string', default: 'pills' },
        tabAccentColor: { type: 'string', default: '' },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const blockProps = useBlockProps({ className: 'giftflow-tab-widget', style: attributes.tabAccentColor ? { '--gf-tab-accent': attributes.tabAccentColor } : {} });
        ensureShimmerStyles();

        const ts = attributes.tabStyle || 'pills';
        const accent = attributes.tabAccentColor || '#3b82f6';
        const tabs = [
            { label: __('Campaign', 'giftflow') },
            { label: __('Donations', 'giftflow') },
            { label: __('Comments', 'giftflow') },
        ];

        const navStyle = ts === 'segments'
            ? { display: 'flex', gap: 0, padding: 4, background: '#e5e7eb', borderRadius: 10, marginBottom: '1.5rem' }
            : ts === 'cards'
            ? { display: 'flex', gap: 4, padding: 0, background: 'transparent', borderRadius: 0, marginBottom: '1.5rem' }
            : ts === 'underline'
            ? { display: 'flex', gap: 0, padding: 0, background: 'transparent', borderRadius: 0, borderBottom: '2px solid #e5e7eb', marginBottom: '1.5rem' }
            : { display: 'flex', gap: 6, padding: 6, background: '#f3f4f6', borderRadius: 12, marginBottom: '1.5rem' };

        const tabActiveStyle = ts === 'segments'
            ? { flex: 1, textAlign: 'center', justifyContent: 'center', padding: '8px 16px', borderRadius: 8, border: 'none', background: '#fff', color: accent, fontWeight: 600, fontSize: 13, cursor: 'pointer', boxShadow: '0 1px 2px rgba(0,0,0,0.06)' }
            : ts === 'cards'
            ? { padding: '10px 20px', borderRadius: '8px 8px 0 0', border: '1px solid #e5e7eb', borderBottom: 'none', background: '#fff', color: accent, fontWeight: 600, fontSize: 14, cursor: 'pointer', marginBottom: -1 }
            : ts === 'underline'
            ? { padding: '12px 20px', borderRadius: 0, border: 'none', borderBottom: '2px solid ' + accent, background: 'transparent', color: accent, fontWeight: 600, fontSize: 14, cursor: 'pointer', marginBottom: -2 }
            : { padding: '10px 20px', borderRadius: 10, border: 'none', background: '#fff', color: accent, fontWeight: 600, fontSize: 14, cursor: 'pointer', boxShadow: '0 1px 3px rgba(0,0,0,0.08)' };

        const tabStyle = ts === 'segments'
            ? { flex: 1, textAlign: 'center', justifyContent: 'center', padding: '8px 16px', borderRadius: 8, border: 'none', background: 'transparent', color: '#6b7280', fontWeight: 500, fontSize: 13, cursor: 'pointer' }
            : ts === 'cards'
            ? { padding: '10px 20px', borderRadius: '8px 8px 0 0', border: '1px solid #e5e7eb', borderBottom: 'none', background: '#f9fafb', color: '#6b7280', fontWeight: 500, fontSize: 14, cursor: 'pointer', marginBottom: -1 }
            : ts === 'underline'
            ? { padding: '12px 20px', borderRadius: 0, border: 'none', borderBottom: '2px solid transparent', background: 'transparent', color: '#6b7280', fontWeight: 500, fontSize: 14, cursor: 'pointer', marginBottom: -2 }
            : { padding: '10px 20px', borderRadius: 10, border: 'none', background: 'transparent', color: '#6b7280', fontWeight: 500, fontSize: 14, cursor: 'pointer' };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Settings', 'giftflow')} initialOpen={true}>
                        <div style={{ marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' }}>{__('Tab style', 'giftflow')}</div>
                        <ToggleGroupControl value={ts} onChange={v => setAttributes({ tabStyle: v })} isBlock __nextHasNoMarginBottom>
                            <ToggleGroupControlOption value="pills" label={__('Pills', 'giftflow')} />
                            <ToggleGroupControlOption value="underline" label={__('Underline', 'giftflow')} />
                            <ToggleGroupControlOption value="cards" label={__('Cards', 'giftflow')} />
                            <ToggleGroupControlOption value="segments" label={__('Segments', 'giftflow')} />
                        </ToggleGroupControl>
                        <div style={{ marginTop: 20 }}>
                            <BaseControl label={__('Accent color', 'giftflow')}>
                                <ColorPalette value={attributes.tabAccentColor} onChange={v => setAttributes({ tabAccentColor: v || '' })} disableCustomColors={false} clearable={true} />
                            </BaseControl>
                        </div>
                    </PanelBody>
                    <PanelBody title={__('About', 'giftflow')} initialOpen={false}>
                        <p style={{ color: '#757575', fontSize: 13 }}>{__('Tabbed layout showing the campaign story, recent donations, and visitor comments. Automatically reads from the current campaign page.', 'giftflow')}</p>
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    <nav className={'giftflow-tab-widget__tabs giftflow-tab-widget__tabs--' + ts} role="tablist" style={navStyle}>
                        {tabs.map((t, i) => (
                            <button key={i} className={'giftflow-tab-widget__tab' + (i === 0 ? ' is-active' : '')} role="tab" aria-selected={i === 0 ? 'true' : 'false'} style={i === 0 ? tabActiveStyle : tabStyle}>{t.label}</button>
                        ))}
                    </nav>
                    <div className="giftflow-tab-widget__content">
                        <div className="giftflow-tab-widget__panel is-active" role="tabpanel">
                            <div style={{ marginBottom: 18 }}>
                                <ShimmerBar height={20} width="45%" style={{ marginBottom: 8 }} />
                                <ShimmerBar height={20} width="30%" />
                            </div>
                            <div style={{ display: 'flex', flexDirection: 'column', gap: 10, marginBottom: 18 }}>
                                <ShimmerBar height={14} width="100%" />
                                <ShimmerBar height={14} width="92%" />
                                <ShimmerBar height={14} width="85%" />
                                <ShimmerBar height={14} width="60%" />
                            </div>
                            <ShimmerBar height={18} width="35%" style={{ marginBottom: 14 }} />
                            <div style={{ display: 'flex', flexDirection: 'column', gap: 10, marginBottom: 18 }}>
                                <ShimmerBar height={14} width="88%" />
                                <ShimmerBar height={14} width="72%" />
                                <ShimmerBar height={14} width="55%" />
                            </div>
                            <ShimmerBox height={160} width="60%" style={{ borderRadius: 8, marginBottom: 18 }} />
                            <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                                <ShimmerBar height={14} width="100%" />
                                <ShimmerBar height={14} width="78%" />
                                <ShimmerBar height={14} width="45%" />
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    },
});
