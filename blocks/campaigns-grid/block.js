import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl, RangeControl, ToggleControl, ColorPalette, BaseControl, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { ShimmerBox, ShimmerBar, ensureShimmerStyles } from '../_editor-utils';

registerBlockType('giftflow/campaigns-grid', {
    apiVersion: 3,
    title: __('Campaigns Grid', 'giftflow'),
    icon: 'grid-view',
    category: 'giftflow',
    attributes: {
        perPage: { type: 'number', default: 9 },
        orderby: { type: 'string', default: 'date' },
        order: { type: 'string', default: 'DESC' },
        category: { type: 'string', default: '' },
        search: { type: 'string', default: '' },
        columns: { type: 'integer', default: 3 },
        cardStyle: { type: 'string', default: 'flat' },
        imageHeight: { type: 'integer', default: 200 },
        imageRatio: { type: 'string', default: 'auto' },
        showProgress: { type: 'boolean', default: true },
        showMeta: { type: 'boolean', default: true },
        progressColor: { type: 'string', default: '' },
        cardBackground: { type: 'string', default: '' },
        inheritCampaignTaxonomy: { type: 'boolean', default: true },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const a = attributes;
        const cols = Math.max(1, Math.min(4, a.columns || 3));
        const perPage = a.perPage || 9;
        const skeletonCards = Math.min(3, perPage);
        const fillColor = a.progressColor || '#2563eb';
        const cardBg = a.cardBackground || '#fff';
        const imgH = a.imageHeight || 200;
        const imgRatio = a.imageRatio || 'auto';
        const useRatio = imgRatio && imgRatio !== 'auto';
        const blockProps = useBlockProps({
            className: `giftflow-campaigns-grid giftflow-campaigns-grid--cols-${cols} giftflow-campaigns-grid--${a.cardStyle || 'shadow'}` + (useRatio ? ' giftflow-campaigns-grid--has-ratio' : ''),
            style: {
                '--giftflow-grid-columns': cols,
                '--gf-grid-img-height': useRatio ? 'auto' : imgH + 'px',
                '--gf-grid-img-ratio': useRatio ? imgRatio : 'auto',
                '--gf-grid-card-bg': cardBg,
            },
        });
        ensureShimmerStyles();

        const [categoryOptions, setCategoryOptions] = useState([{ label: __('All categories', 'giftflow'), value: '' }]);
        useEffect(() => { apiFetch({ path: '/wp/v2/campaign-tax?per_page=100' }).then(terms => { if (Array.isArray(terms)) setCategoryOptions([{ label: __('All categories', 'giftflow'), value: '' }, ...terms.map(t => ({ label: t.name, value: String(t.id) }))]); }).catch(() => {}); }, []);

        const selectedCategory = a.category ? categoryOptions.find(c => c.value === a.category) : null;

        const lb = { marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Query', 'giftflow')} initialOpen={true}>
                        <RangeControl label={__('Per page', 'giftflow')} value={perPage} onChange={v => setAttributes({ perPage: v == null ? 9 : v })} min={1} max={48} />
                        <SelectControl label={__('Order by', 'giftflow')} value={a.orderby} options={[{ label: __('Date', 'giftflow'), value: 'date' }, { label: __('Title', 'giftflow'), value: 'title' }, { label: __('Modified', 'giftflow'), value: 'modified' }, { label: __('Menu order', 'giftflow'), value: 'menu_order' }]} onChange={v => setAttributes({ orderby: v })} />
                        <SelectControl label={__('Order', 'giftflow')} value={a.order} options={[{ label: __('Newest first', 'giftflow'), value: 'DESC' }, { label: __('Oldest first', 'giftflow'), value: 'ASC' }]} onChange={v => setAttributes({ order: v })} />
                        <ToggleControl label={__('Match archive category', 'giftflow')} checked={a.inheritCampaignTaxonomy !== false} onChange={v => setAttributes({ inheritCampaignTaxonomy: v })} />
                        <SelectControl label={__('Filter by category', 'giftflow')} value={a.category} options={categoryOptions} onChange={v => setAttributes({ category: v })} />
                        <TextControl label={__('Search keyword', 'giftflow')} value={a.search} onChange={v => setAttributes({ search: v })} />
                    </PanelBody>
                    <PanelBody title={__('Layout', 'giftflow')} initialOpen={false}>
                        <RangeControl label={__('Columns', 'giftflow')} value={cols} onChange={v => setAttributes({ columns: v })} min={1} max={4} />
                        <SelectControl
                            label={__('Image ratio', 'giftflow')}
                            value={imgRatio}
                            options={[
                                { label: __('Auto (fixed height)', 'giftflow'), value: 'auto' },
                                { label: __('Square 1:1', 'giftflow'), value: '1/1' },
                                { label: __('Standard 4:3', 'giftflow'), value: '4/3' },
                                { label: __('Widescreen 16:9', 'giftflow'), value: '16/9' },
                                { label: __('Classic 3:2', 'giftflow'), value: '3/2' },
                                { label: __('Portrait 2:3', 'giftflow'), value: '2/3' },
                            ]}
                            onChange={v => setAttributes({ imageRatio: v })}
                        />
                        {!useRatio && <RangeControl label={__('Image height', 'giftflow')} value={imgH} onChange={v => setAttributes({ imageHeight: v })} min={120} max={360} step={10} />}
                        <div style={{ marginBottom: 20 }}>
                            <div style={lb}>{__('Card style', 'giftflow')}</div>
                            <ToggleGroupControl value={a.cardStyle || 'flat'} onChange={v => setAttributes({ cardStyle: v })} isBlock __nextHasNoMarginBottom>
                                <ToggleGroupControlOption value="flat" label={__('Flat', 'giftflow')} />
                                <ToggleGroupControlOption value="minimal" label={__('Minimal', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                    </PanelBody>
                    <PanelBody title={__('Content', 'giftflow')} initialOpen={false}>
                        <ToggleControl label={__('Show progress bar', 'giftflow')} checked={a.showProgress} onChange={v => setAttributes({ showProgress: v })} />
                        <ToggleControl label={__('Show meta info', 'giftflow')} checked={a.showMeta} onChange={v => setAttributes({ showMeta: v })} />
                    </PanelBody>
                    <PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
                        <BaseControl label={__('Accent color', 'giftflow')}>
                            <ColorPalette value={a.progressColor} onChange={v => setAttributes({ progressColor: v || '' })} disableCustomColors={false} clearable={true} />
                        </BaseControl>
                        <BaseControl label={__('Card background', 'giftflow')}>
                            <ColorPalette value={a.cardBackground} onChange={v => setAttributes({ cardBackground: v || '' })} disableCustomColors={false} clearable={true} />
                        </BaseControl>
                    </PanelBody>
                </InspectorControls>
                <div {...blockProps}>
                    {(selectedCategory || a.search || perPage !== 9 || cols !== 3) && (
                        <div style={{ fontSize: 11, color: '#6b7280', marginBottom: 14, display: 'flex', flexWrap: 'wrap', gap: 8, alignItems: 'center' }}>
                            <span style={{ fontWeight: 500 }}>{__('Showing', 'giftflow')} {skeletonCards} {__('of', 'giftflow')} {perPage}</span>
                            {selectedCategory && <span style={{ background: '#f3f4f6', color: '#374151', padding: '2px 8px', borderRadius: 10, fontSize: 10, fontWeight: 500 }}>{selectedCategory.label}</span>}
                            {a.search && <span style={{ background: '#fef3c7', color: '#92400e', padding: '2px 8px', borderRadius: 10, fontSize: 10, fontWeight: 500 }}>&ldquo;{a.search}&rdquo;</span>}
                        </div>
                    )}
                    <div className="giftflow-campaigns-grid__items">
                        {Array.from({ length: skeletonCards }).map((_, i) => (
                            <article key={i} className="giftflow-campaigns-grid__item" style={a.cardStyle === 'minimal' ? { border: 'none', boxShadow: 'none', background: 'transparent' } : { border: '1px solid #e5e7eb', boxShadow: 'none', background: cardBg }}>
                                <div className="giftflow-campaigns-grid__image" style={{ position: 'relative' }}>
                                    <ShimmerBox height={useRatio ? undefined : imgH} style={{ borderRadius: 0, ...(useRatio ? { aspectRatio: imgRatio, height: 'auto' } : {}) }} />
                                    <div style={{ position: 'absolute', top: 12, left: 12, padding: '4px 10px', background: 'rgba(255,255,255,0.9)', borderRadius: 6, fontSize: 11, fontWeight: 600, color: fillColor }}>{__('Category', 'giftflow')}</div>
                                </div>
                                <div className="giftflow-campaigns-grid__body" style={{ display: 'flex', flexDirection: 'column', ...(a.cardStyle === 'minimal' ? { padding: '14px 0 0' } : {}) }}>
                                    <h4 className="giftflow-campaigns-grid__title"><ShimmerBar height={16} width="80%" /></h4>
                                    <div style={{ margin: '0 0 12px' }}><ShimmerBar height={12} width="95%" /><ShimmerBar height={12} width="65%" style={{ marginTop: 6 }} /></div>
                                    {a.showProgress && (
                                        <div className="giftflow-campaigns-grid__progress">
                                            <div className="giftflow-campaigns-grid__progress-bar">
                                                <div className="giftflow-campaigns-grid__progress-fill" style={{ width: `${25 + i * 25}%`, ...(fillColor ? { background: fillColor } : {}) }}></div>
                                            </div>
                                            <span className="giftflow-campaigns-grid__progress-text">{25 + i * 25}%</span>
                                        </div>
                                    )}
                                    {a.showMeta && (
                                        <div className="giftflow-campaigns-grid__meta">
                                            <span className="giftflow-campaigns-grid__raised"><ShimmerBar height={12} width={70} /></span>
                                            <span className="giftflow-campaigns-grid__goal"><ShimmerBar height={12} width={80} /></span>
                                            <span className="giftflow-campaigns-grid__days" style={{ fontSize: 12, fontWeight: 500, color: fillColor }}>18 {__('days left', 'giftflow')}</span>
                                        </div>
                                    )}
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6, padding: '10px 16px', marginTop: 'auto', fontSize: 13, fontWeight: 600, color: '#fff', background: fillColor, borderRadius: 10, textDecoration: 'none' }}>
                                        {__('Read more', 'giftflow')} →
                                    </div>
                                </div>
                            </article>
                        ))}
                    </div>
                    {perPage > skeletonCards && (
                        <nav className="giftflow-campaigns-grid__pagination" style={{ marginTop: 16, textAlign: 'center' }}>
                            <span style={{ fontSize: 12, color: '#9ca3af' }}>+{perPage - skeletonCards} {__('more', 'giftflow')}</span>
                        </nav>
                    )}
                </div>
            </>
        );
    },
});
