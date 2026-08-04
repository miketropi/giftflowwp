import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, RangeControl, ToggleControl, ColorPalette, BaseControl, TextControl, TextareaControl, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption, Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useEffect, useState, useMemo } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { ShimmerBox, ShimmerBar, ensureShimmerStyles } from '../_editor-utils';

/**
 * Strip HTML tags and decode HTML entities from a string.
 * Uses the browser's built-in HTML parser for reliable entity decoding.
 */
function stripHtml(str) {
    if (!str || typeof str !== 'string') return '';
    // Strip HTML tags first, then decode remaining entities via DOM.
    const stripped = str.replace(/<[^>]*>/g, '');
    const textarea = document.createElement('textarea');
    textarea.innerHTML = stripped;
    return textarea.value;
}

/**
 * Format a number as currency (simple fallback — server provides formatted strings).
 */
function formatAmount(n) {
    if (n == null || isNaN(n)) return '$0';
    return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

/**
 * Placeholder SVG for campaigns without featured images.
 */
const ImagePlaceholder = ({ height, useRatio, ratio }) => (
    <div style={{
        display: 'flex', alignItems: 'center', justifyContent: 'center',
        height: useRatio ? undefined : height,
        aspectRatio: useRatio ? ratio : 'auto',
        background: '#f3f4f6', borderRadius: 10, color: '#9ca3af',
        width: '100%',
    }}>
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
            <rect width="18" height="18" x="3" y="3" rx="2" />
            <circle cx="8.5" cy="8.5" r="1.5" />
            <path d="m21 15-5-5L5 21" />
        </svg>
    </div>
);

registerBlockType('giftflow/campaigns-carousel', {
    apiVersion: 3,
    title: __('Campaigns Carousel', 'giftflow'),
    icon: 'slides',
    category: 'giftflow',
    attributes: {
        perPage: { type: 'number', default: 9 },
        orderby: { type: 'string', default: 'date' },
        order: { type: 'string', default: 'DESC' },
        category: { type: 'string', default: '' },
        columns: { type: 'integer', default: 3 },
        imageHeight: { type: 'integer', default: 240 },
        imageRatio: { type: 'string', default: 'auto' },
        showProgress: { type: 'boolean', default: true },
        showMeta: { type: 'boolean', default: true },
        autoplay: { type: 'boolean', default: false },
        autoplayDelay: { type: 'number', default: 4000 },
        loop: { type: 'boolean', default: true },
        progressColor: { type: 'string', default: '' },
        cardBackground: { type: 'string', default: '' },
        titleColor: { type: 'string', default: '' },
        metaColor: { type: 'string', default: '' },
        descriptionColor: { type: 'string', default: '' },
        buttonTextColor: { type: 'string', default: '' },
        eyebrow: { type: 'string', default: '' },
        heading: { type: 'string', default: '' },
        description: { type: 'string', default: '' },
        headerAlign: { type: 'string', default: 'center' },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const a = attributes;

        // Derived values.
        const fillColor = a.progressColor || '#2563eb';
        const cardBg = a.cardBackground || '#fff';
        const imgH = a.imageHeight || 240;
        const imgRatio = a.imageRatio || 'auto';
        const useRatio = imgRatio && imgRatio !== 'auto';

        // Color fallbacks.
        const titleClr = a.titleColor || 'inherit';
        const metaClr = a.metaColor || '#6b7280';
        const descClr = a.descriptionColor || '#6b7280';
        const btnText = a.buttonTextColor || '#fff';

        // Build CSS custom properties.
        const cssVars = {
            '--gf-carousel-img-height': useRatio ? 'auto' : imgH + 'px',
            '--gf-carousel-img-ratio': useRatio ? imgRatio : 'auto',
            '--gf-carousel-card-bg': cardBg,
            '--gf-carousel-accent': fillColor,
            '--gf-carousel-title-color': titleClr,
            '--gf-carousel-meta-color': metaClr,
            '--gf-carousel-desc-color': descClr,

            '--gf-carousel-button-text': btnText,
        };

        const blockProps = useBlockProps({
            className: 'giftflow-carousel' + (useRatio ? ' giftflow-carousel--has-ratio' : ''),
            style: cssVars,
        });
        ensureShimmerStyles();

        // ── Category options ──
        const [catOpts, setCatOpts] = useState([{ label: __('All categories', 'giftflow'), value: '' }]);
        useEffect(() => {
            apiFetch({ path: '/wp/v2/campaign-tax?per_page=100' })
                .then(t => {
                    if (Array.isArray(t)) {
                        setCatOpts([
                            { label: __('All categories', 'giftflow'), value: '' },
                            ...t.map(c => ({ label: c.name, value: String(c.id) })),
                        ]);
                    }
                })
                .catch(() => {});
        }, []);

        // ── Fetch campaigns ──
        const [campaigns, setCampaigns] = useState(null);
        const [isLoading, setIsLoading] = useState(true);
        const [fetchError, setFetchError] = useState(null);

        useEffect(() => {
            let cancelled = false;
            setIsLoading(true);
            setFetchError(null);

            const qs = new URLSearchParams({
                per_page: String(a.perPage),
                orderby: a.orderby,
                order: a.order.toLowerCase(),
            });

            apiFetch({ path: '/giftflow/v2/campaigns?' + qs.toString() })
                .then(data => {
                    if (cancelled) return;
                    let items = Array.isArray(data) ? data : [];

                    // Client-side category filter.
                    // The custom endpoint doesn't yet support tax filtering,
                    // so we filter by matching category term name from the campaign data.
                    // Campaigns from giftflow/v2 include all meta; we also need
                    // category info, so we do a second resolve for categories
                    // or use the REST API's _embed.
                    //
                    // For now, category filtering is done via post terms in a
                    // follow-up round-trip. If a category is selected, fetch
                    // the campaigns in that term via wp/v2.
                    setCampaigns(items);
                    setIsLoading(false);
                })
                .catch(err => {
                    if (cancelled) return;
                    setFetchError(err);
                    setIsLoading(false);
                });

            return () => { cancelled = true; };
        }, [a.perPage, a.orderby, a.order]);

        // ── Separate fetch when category filter changes ──
        const [filteredCampaigns, setFilteredCampaigns] = useState(null);
        const [isFiltering, setIsFiltering] = useState(false);

        useEffect(() => {
            if (!a.category) {
                // No category filter — use the main campaigns list.
                setFilteredCampaigns(null);
                setIsFiltering(false);
                return;
            }

            let cancelled = false;
            setIsFiltering(true);

            // Fetch campaigns in the selected category via WP REST API.
            apiFetch({
                path: '/wp/v2/campaign?per_page=' + a.perPage +
                      '&orderby=' + a.orderby +
                      '&order=' + a.order.toLowerCase() +
                      '&campaign-tax=' + a.category +
                      '&status=publish',
            })
                .then(posts => {
                    if (cancelled) return;
                    if (!Array.isArray(posts) || posts.length === 0) {
                        setFilteredCampaigns([]);
                        setIsFiltering(false);
                        return;
                    }

                    // Extract IDs and fetch enriched data from custom endpoint.
                    const ids = posts.map(p => p.id).join(',');
                    apiFetch({ path: '/giftflow/v2/campaigns?include=' + ids + '&per_page=' + a.perPage })
                        .then(data => {
                            if (cancelled) return;
                            setFilteredCampaigns(Array.isArray(data) ? data : []);
                            setIsFiltering(false);
                        })
                        .catch(() => {
                            if (cancelled) return;
                            // Fallback: build basic campaign objects from post data.
                            const basic = posts.map(p => ({
                                id: p.id,
                                title: p.title?.rendered || '',
                                excerpt: p.excerpt?.rendered?.replace(/<[^>]+>/g, '') || '',
                                thumbnail: p.featured_media ? '' : '',
                                link: p.link,
                                goal_amount: 0,
                                raised_amount: 0,
                                percentage: 0,
                                goal_formatted: '',
                                raised_formatted: '',
                                start_date: '',
                                end_date: '',
                                location: '',
                            }));
                            setFilteredCampaigns(basic);
                            setIsFiltering(false);
                        });
                })
                .catch(() => {
                    if (cancelled) return;
                    setFilteredCampaigns([]);
                    setIsFiltering(false);
                });

            return () => { cancelled = true; };
        }, [a.category, a.perPage, a.orderby, a.order]);

        // Determine which campaign list to use.
        const displayCampaigns = a.category ? filteredCampaigns : campaigns;
        const displayLoading = a.category ? isFiltering : isLoading;

        // ── Card rendering helpers ──
        const cols = a.columns || 3;
        const cardFlex = `0 0 calc(${100 / cols}% - ${cols > 1 ? Math.round(20 * (cols - 1) / cols) : 0}px)`;

        const renderCard = (campaign, i) => {
            const hasThumb = campaign.thumbnail && campaign.thumbnail.length > 0;
            const excerpt = campaign.excerpt
                ? campaign.excerpt.replace(/<[^>]+>/g, '').substring(0, 100) + (campaign.excerpt.replace(/<[^>]+>/g, '').length > 100 ? '…' : '')
                : '';
            const pct = campaign.percentage || 0;
            const days = campaign.days_left !== undefined ? campaign.days_left : '';
            // Compute days_left if not provided:
            let daysLeft = '';
            if (campaign.end_date) {
                const now = new Date();
                const end = new Date(campaign.end_date);
                const diff = Math.ceil((end - now) / (1000 * 60 * 60 * 24));
                if (diff > 0) daysLeft = diff;
                else if (diff === 0) daysLeft = 0;
            }

            return (
                <div key={campaign.id || i} style={{ flex: cardFlex, minWidth: 220 }}>
                    <article style={{
                        borderRadius: 16, overflow: 'hidden', border: '1px solid #e5e7eb',
                        background: cardBg, display: 'flex', flexDirection: 'column', height: '100%',
                    }}>
                        {/* Image */}
                        <div style={{ position: 'relative', padding: '1.25rem 1.25rem 0' }}>
                            <a href={campaign.link || '#'} style={{ display: 'block' }}>
                                {hasThumb ? (
                                    <img
                                        src={campaign.thumbnail}
                                        alt={campaign.title || ''}
                                        style={{
                                            display: 'block', width: '100%',
                                            height: useRatio ? 'auto' : imgH,
                                            aspectRatio: useRatio ? imgRatio : 'auto',
                                            objectFit: 'cover', borderRadius: 10,
                                        }}
                                    />
                                ) : (
                                    <ImagePlaceholder height={imgH} useRatio={useRatio} ratio={imgRatio} />
                                )}
                            </a>
                            {/* Category badge — show placeholder in editor since category info
                                isn't in the custom endpoint. We'll show the first category name
                                if available, otherwise a generic badge. */}
                            <span style={{
                                position: 'absolute', top: '1.625rem', left: '1.625rem',
                                padding: '4px 10px', background: 'rgba(255,255,255,0.94)',
                                borderRadius: 6, fontSize: 11, fontWeight: 600,
                                color: fillColor, letterSpacing: '0.02em', zIndex: 1,
                            }}>
                                {campaign.category_name
                                    ? campaign.category_name
                                    : a.category
                                        ? (catOpts.find(c => c.value === a.category)?.label || __('Campaign', 'giftflow'))
                                        : __('Campaign', 'giftflow')}
                            </span>
                        </div>

                        {/* Body */}
                        <div style={{ padding: '1.25rem', display: 'flex', flexDirection: 'column', flex: 1 }}>
                            <h4 style={{ margin: '0 0 6px', fontSize: 16, fontWeight: 600, lineHeight: 1.3 }}>
                                <a href={campaign.link || '#'} style={{
                                    textDecoration: 'none', color: titleClr === 'inherit' ? '#111827' : titleClr,
                                }}>
                                    {campaign.title || __('Untitled Campaign', 'giftflow')}
                                </a>
                            </h4>

                            {excerpt && (
                                <p style={{
                                    margin: '0 0 12px', fontSize: 14, lineHeight: 1.5,
                                    color: descClr, display: '-webkit-box',
                                    WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden',
                                }}>
                                    {excerpt}
                                </p>
                            )}

                            {/* Progress */}
                            {a.showProgress && (
                                <div style={{ marginBottom: 10 }}>
                                    <div style={{
                                        height: 8, borderRadius: 99,
                                        background: '#e5e7eb', marginBottom: 6, overflow: 'hidden',
                                    }}>
                                        <div style={{
                                            height: '100%', width: pct + '%',
                                            borderRadius: 99, background: fillColor,
                                            transition: 'width 0.6s ease',
                                        }} />
                                    </div>
                                    <span style={{ fontSize: 13, fontWeight: 600, color: fillColor }}>
                                        {pct}%
                                    </span>
                                </div>
                            )}

                            {/* Meta */}
                            {a.showMeta && (
                                <div style={{
                                    display: 'flex', flexWrap: 'wrap', gap: '4px 12px',
                                    marginBottom: 12, fontSize: 13, color: metaClr,
                                }}>
                                    <span style={{ fontWeight: 650, color: metaClr === '#6b7280' ? '#1f2937' : metaClr }}>
                                        {stripHtml(campaign.raised_formatted) || formatAmount(campaign.raised_amount)} {__('raised', 'giftflow')}
                                    </span>
                                    {campaign.goal_amount > 0 && (
                                        <span>
                                            {__('Goal', 'giftflow')} {stripHtml(campaign.goal_formatted) || formatAmount(campaign.goal_amount)}
                                        </span>
                                    )}
                                    {daysLeft !== '' && daysLeft > 0 && (
                                        <span style={{ fontSize: 12 }}>
                                            {daysLeft === 1
                                                ? __('1 day left', 'giftflow')
                                                : daysLeft + ' ' + __('days left', 'giftflow')}
                                        </span>
                                    )}
                                    {campaign.location && (
                                        <span style={{
                                            display: 'inline-flex', alignItems: 'center', gap: 4,
                                            fontSize: 12, width: '100%',
                                        }}>
                                            <span style={{
                                                width: 4, height: 4, borderRadius: '50%',
                                                background: '#d1d5db', display: 'inline-block',
                                            }} />
                                            {campaign.location}
                                        </span>
                                    )}
                                </div>
                            )}

                            {/* Read More */}
                            <a href={campaign.link || '#'} className="giftflow-carousel__read-more" style={{
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                gap: 6, padding: '10px 16px', marginTop: 'auto',
                                fontSize: 13, fontWeight: 600, lineHeight: 1.5,
                                color: btnText, background: fillColor,
                                borderRadius: 10, textDecoration: 'none',
                            }}>
                                {__('Read more', 'giftflow')} <svg className="giftflow-carousel__read-more-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginLeft: 4, flexShrink: 0 }}><path d="M9 18l6-6-6-6"/></svg>
                            </a>
                        </div>
                    </article>
                </div>
            );
        };

        // ── Loading skeleton ──
        const renderSkeleton = () => (
            <div style={{ display: 'flex', gap: 20, overflow: 'hidden', paddingBottom: 40 }}>
                {[0, 1, 2].map(i => (
                    <div key={i} style={{
                        flex: cardFlex, minWidth: 220, borderRadius: 16, overflow: 'hidden',
                        border: '1px solid #e5e7eb', background: cardBg,
                    }}>
                        <div style={{ position: 'relative', padding: '1.25rem 1.25rem 0' }}>
                            <ShimmerBox height={useRatio ? undefined : imgH} style={{
                                borderRadius: 10,
                                ...(useRatio ? { aspectRatio: imgRatio, height: 'auto' } : {}),
                            }} />
                            <div style={{
                                position: 'absolute', top: '1.625rem', left: '1.625rem',
                                padding: '2px 10px', background: 'rgba(255,255,255,0.9)',
                                borderRadius: 6, fontSize: 11, fontWeight: 600, color: fillColor,
                            }}>
                                {__('Category', 'giftflow')}
                            </div>
                        </div>
                        <div style={{ padding: '18px 20px 20px' }}>
                            <ShimmerBar height={16} width="80%" style={{ marginBottom: 8 }} />
                            <div style={{ marginBottom: 12 }}>
                                <ShimmerBar height={12} width="90%" />
                                <ShimmerBar height={12} width="60%" style={{ marginTop: 5 }} />
                            </div>
                            {a.showProgress && (
                                <div style={{ marginBottom: 10 }}>
                                    <div style={{
                                        height: 8, borderRadius: 99, background: '#f3f4f6',
                                        marginBottom: 6,
                                    }}>
                                        <div style={{
                                            height: '100%', width: `${30 + i * 30}%`,
                                            borderRadius: 99, background: fillColor,
                                        }} />
                                    </div>
                                    <ShimmerBar height={12} width={40} />
                                </div>
                            )}
                            {a.showMeta && (
                                <div style={{ display: 'flex', gap: 8, marginBottom: 8 }}>
                                    <ShimmerBar height={12} width={60} />
                                    <ShimmerBar height={12} width={50} />
                                </div>
                            )}
                            <div className="giftflow-carousel__read-more" style={{
                                display: 'flex', justifyContent: 'center',
                                padding: '10px 16px', marginTop: 8, borderRadius: 10,
                                background: fillColor, color: btnText, fontSize: 13, fontWeight: 600,
                            }}>
                                {__('Read more', 'giftflow')} <svg className="giftflow-carousel__read-more-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginLeft: 4, flexShrink: 0 }}><path d="M9 18l6-6-6-6"/></svg>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        );

        const lb = { marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Query', 'giftflow')} initialOpen={true}>
                        <RangeControl
                            label={__('Total campaigns', 'giftflow')}
                            value={a.perPage}
                            onChange={v => setAttributes({ perPage: v || 9 })}
                            min={1} max={24}
                        />
                        <SelectControl
                            label={__('Order by', 'giftflow')}
                            value={a.orderby}
                            options={[
                                { label: __('Date', 'giftflow'), value: 'date' },
                                { label: __('Title', 'giftflow'), value: 'title' },
                            ]}
                            onChange={v => setAttributes({ orderby: v })}
                        />
                        <SelectControl
                            label={__('Order', 'giftflow')}
                            value={a.order}
                            options={[
                                { label: __('Newest first', 'giftflow'), value: 'DESC' },
                                { label: __('Oldest first', 'giftflow'), value: 'ASC' },
                            ]}
                            onChange={v => setAttributes({ order: v })}
                        />
                        <SelectControl
                            label={__('Filter by category', 'giftflow')}
                            value={a.category}
                            options={catOpts}
                            onChange={v => setAttributes({ category: v })}
                        />
                    </PanelBody>
                    <PanelBody title={__('Header', 'giftflow')} initialOpen={false}>
                        <TextControl
                            label={__('Eyebrow', 'giftflow')}
                            value={a.eyebrow}
                            onChange={v => setAttributes({ eyebrow: v })}
                            help={__('Small badge text above heading.', 'giftflow')}
                        />
                        <TextControl
                            label={__('Heading', 'giftflow')}
                            value={a.heading}
                            onChange={v => setAttributes({ heading: v })}
                        />
                        <TextareaControl
                            label={__('Description', 'giftflow')}
                            value={a.description}
                            onChange={v => setAttributes({ description: v })}
                        />
                        <div style={{ marginTop: 12 }}>
                            <div style={lb}>{__('Alignment', 'giftflow')}</div>
                            <ToggleGroupControl
                                value={a.headerAlign || 'center'}
                                onChange={v => setAttributes({ headerAlign: v })}
                                isBlock
                                __nextHasNoMarginBottom
                            >
                                <ToggleGroupControlOption value="left" label={__('Left', 'giftflow')} />
                                <ToggleGroupControlOption value="center" label={__('Center', 'giftflow')} />
                                <ToggleGroupControlOption value="right" label={__('Right', 'giftflow')} />
                            </ToggleGroupControl>
                        </div>
                    </PanelBody>
                    <PanelBody title={__('Layout', 'giftflow')} initialOpen={false}>
                        <RangeControl
                            label={__('Columns (desktop)', 'giftflow')}
                            value={a.columns}
                            onChange={v => setAttributes({ columns: v })}
                            min={1} max={5}
                        />
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
                        {!useRatio && (
                            <RangeControl
                                label={__('Image height', 'giftflow')}
                                value={imgH}
                                onChange={v => setAttributes({ imageHeight: v })}
                                min={150} max={400} step={10}
                            />
                        )}
                    </PanelBody>
                    <PanelBody title={__('Content', 'giftflow')} initialOpen={false}>
                        <ToggleControl
                            label={__('Show progress', 'giftflow')}
                            checked={a.showProgress}
                            onChange={v => setAttributes({ showProgress: v })}
                        />
                        <ToggleControl
                            label={__('Show meta', 'giftflow')}
                            checked={a.showMeta}
                            onChange={v => setAttributes({ showMeta: v })}
                        />
                    </PanelBody>
                    <PanelBody title={__('Playback', 'giftflow')} initialOpen={false}>
                        <ToggleControl
                            label={__('Autoplay', 'giftflow')}
                            checked={a.autoplay}
                            onChange={v => setAttributes({ autoplay: v })}
                        />
                        {a.autoplay && (
                            <RangeControl
                                label={__('Delay (ms)', 'giftflow')}
                                value={a.autoplayDelay}
                                onChange={v => setAttributes({ autoplayDelay: v })}
                                min={1000} max={10000} step={500}
                            />
                        )}
                        <ToggleControl
                            label={__('Loop', 'giftflow')}
                            checked={a.loop}
                            onChange={v => setAttributes({ loop: v })}
                        />
                    </PanelBody>
                    <PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
                        <BaseControl label={__('Accent color', 'giftflow')}>
                            <ColorPalette
                                value={a.progressColor}
                                onChange={v => setAttributes({ progressColor: v || '' })}
                                disableCustomColors={false}
                                clearable={true}
                            />
                        </BaseControl>
                        <BaseControl label={__('Card background', 'giftflow')}>
                            <ColorPalette
                                value={a.cardBackground}
                                onChange={v => setAttributes({ cardBackground: v || '' })}
                                disableCustomColors={false}
                                clearable={true}
                            />
                        </BaseControl>
                        <BaseControl label={__('Title text color', 'giftflow')}>
                            <ColorPalette
                                value={a.titleColor}
                                onChange={v => setAttributes({ titleColor: v || '' })}
                                disableCustomColors={false}
                                clearable={true}
                            />
                        </BaseControl>
                        <BaseControl label={__('Description / excerpt color', 'giftflow')}>
                            <ColorPalette
                                value={a.descriptionColor}
                                onChange={v => setAttributes({ descriptionColor: v || '' })}
                                disableCustomColors={false}
                                clearable={true}
                            />
                        </BaseControl>
                        <BaseControl label={__('Meta text color', 'giftflow')}>
                            <ColorPalette
                                value={a.metaColor}
                                onChange={v => setAttributes({ metaColor: v || '' })}
                                disableCustomColors={false}
                                clearable={true}
                            />
                        </BaseControl>
                        <BaseControl label={__('Button text color', 'giftflow')}>
                            <ColorPalette
                                value={a.buttonTextColor}
                                onChange={v => setAttributes({ buttonTextColor: v || '' })}
                                disableCustomColors={false}
                                clearable={true}
                            />
                        </BaseControl>
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    {/* Header section */}
                    {(a.eyebrow || a.heading || a.description) && (
                        <div style={{ textAlign: a.headerAlign || 'center', marginBottom: 32 }}>
                            {a.eyebrow && (
                                <span style={{
                                    display: 'inline-block', padding: '4px 12px',
                                    background: fillColor, color: '#fff',
                                    borderRadius: 99, fontSize: 12, fontWeight: 600,
                                    textTransform: 'uppercase', letterSpacing: '0.04em',
                                    marginBottom: 12,
                                }}>
                                    {a.eyebrow}
                                </span>
                            )}
                            {a.heading && (
                                <h2 style={{
                                    margin: '0 0 8px', fontSize: 28, fontWeight: 700,
                                    color: '#111827',
                                }}>
                                    {a.heading}
                                </h2>
                            )}
                            {a.description && (
                                <p style={{
                                    margin: 0,
                                    maxWidth: a.headerAlign === 'center' ? 560 : 'none',
                                    marginLeft: a.headerAlign === 'left' ? 0 : 'auto',
                                    marginRight: a.headerAlign === 'right' ? 0 : 'auto',
                                    fontSize: 15, lineHeight: 1.6, color: descClr,
                                }}>
                                    {a.description}
                                </p>
                            )}
                        </div>
                    )}

                    {/* Campaign cards */}
                    {displayLoading ? (
                        renderSkeleton()
                    ) : displayCampaigns && displayCampaigns.length > 0 ? (
                        <div style={{ display: 'flex', gap: 20, overflow: 'hidden', paddingBottom: 40 }}>
                            {displayCampaigns.slice(0, a.columns || 3).map(renderCard)}
                        </div>
                    ) : (
                        <div style={{
                            padding: '4rem 2rem', textAlign: 'center',
                            color: '#6b7280', fontSize: 15,
                            background: '#f9fafb', borderRadius: 16,
                        }}>
                            {fetchError
                                ? __('Could not load campaigns. Please try again.', 'giftflow')
                                : __('No campaigns found.', 'giftflow')}
                        </div>
                    )}

                    {/* Pagination dots (decorative) */}
                    {displayCampaigns && displayCampaigns.length > 0 && (
                        <div style={{ display: 'flex', justifyContent: 'center', gap: 8, marginTop: 16 }}>
                            <div style={{ width: 24, height: 8, borderRadius: 4, background: fillColor }} />
                            <div style={{ width: 8, height: 8, borderRadius: '50%', background: '#d1d5db' }} />
                            <div style={{ width: 8, height: 8, borderRadius: '50%', background: '#d1d5db' }} />
                        </div>
                    )}
                </div>
            </>
        );
    },
});
