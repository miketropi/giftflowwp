import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	ToggleControl,
	ColorPalette,
	BaseControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { ShimmerBox, ShimmerBar, ensureShimmerStyles, useCampaignSelector } from '../_editor-utils';

function stripHtml(str) {
	if (!str || typeof str !== 'string') return '';
	const stripped = str.replace(/<[^>]*>/g, '');
	const textarea = document.createElement('textarea');
	textarea.innerHTML = stripped;
	return textarea.value;
}

registerBlockType('giftflow/campaign-list', {
	apiVersion: 3,
	title: __('Campaign List', 'giftflow'),
	icon: 'list-view',
	category: 'giftflow',
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const a = attributes;
		const campaignId = a.campaignId || 0;
		const accent = a.accentColor || '#2563eb';
		const imageRatio = a.imageRatio || '4/3';

		const cssVars = {
			'--gf-cl-accent': accent,
			'--gf-cl-img-ratio': imageRatio,
		};
		if (a.cardBackground) cssVars['--gf-cl-card-bg'] = a.cardBackground;
		if (a.titleColor) cssVars['--gf-cl-title-color'] = a.titleColor;
		if (a.metaColor) cssVars['--gf-cl-meta-color'] = a.metaColor;
		if (a.categoryColor) cssVars['--gf-cl-category-color'] = a.categoryColor;
		if (a.captionColor) cssVars['--gf-cl-caption-color'] = a.captionColor;

		const blockProps = useBlockProps({
			className: 'gf-cl',
			style: cssVars,
		});
		ensureShimmerStyles();

		const { CampaignSelector } = useCampaignSelector({ defaultLabel: __('Select a campaign…', 'giftflow') });

		const [catOpts, setCatOpts] = useState([{ label: __('All categories', 'giftflow'), value: '' }]);
		useEffect(() => {
			apiFetch({ path: '/wp/v2/campaign-tax?per_page=100' })
				.then((t) => {
					if (Array.isArray(t)) {
						setCatOpts([
							{ label: __('All categories', 'giftflow'), value: '' },
							...t.map((c) => ({ label: c.name, value: String(c.id) })),
						]);
					}
				})
				.catch(() => {});
		}, []);

		const [campaigns, setCampaigns] = useState(null);
		const [isLoading, setIsLoading] = useState(true);

		useEffect(() => {
			if (campaignId > 0) {
				let cancelled = false;
				setIsLoading(true);
				apiFetch({ path: `/giftflow/v2/campaigns?include=${campaignId}&per_page=1` })
					.then((data) => {
						if (cancelled) return;
						setCampaigns(Array.isArray(data) && data.length > 0 ? [data[0]] : []);
						setIsLoading(false);
					})
					.catch(() => { if (!cancelled) { setCampaigns([]); setIsLoading(false); } });
				return () => { cancelled = true; };
			}

			let cancelled = false;
			setIsLoading(true);
			const qs = new URLSearchParams({
				per_page: String(a.perPage || 6),
				orderby: a.orderby || 'date',
				order: (a.order || 'DESC').toLowerCase(),
			});
			apiFetch({ path: '/giftflow/v2/campaigns?' + qs.toString() })
				.then((data) => {
					if (cancelled) return;
					setCampaigns(Array.isArray(data) ? data : []);
					setIsLoading(false);
				})
				.catch(() => { if (!cancelled) { setCampaigns([]); setIsLoading(false); } });
			return () => { cancelled = true; };
		}, [campaignId, a.perPage, a.orderby, a.order, a.category]);

		const shimmer = isLoading || !campaigns;

		const renderSkeleton = () => (
			<div className="gf-cl-list">
				{[0, 1, 2].map((i) => (
					<div key={i} style={{
						display: 'flex', flexDirection: 'row-reverse',
						gap: 28, padding: '28px 0',
						borderTop: i > 0 ? '1px solid #f0f0f0' : 'none',
					}}>
						<div style={{
							flex: 'none', width: 200,
							aspectRatio: imageRatio,
							overflow: 'hidden', background: '#e8eaee',
							position: 'relative',
						}}>
							<ShimmerBox height="100%" style={{ position: 'absolute', inset: 0, borderRadius: 0 }} />
						</div>
						<div style={{ flex: 1, minWidth: 0, display: 'flex', flexDirection: 'column', gap: 6 }}>
							<ShimmerBar height={10} width={70} style={{ marginBottom: 2 }} />
							<ShimmerBar height={18} width="75%" style={{ marginBottom: 2 }} />
							<ShimmerBar height={13} width="100%" style={{ marginBottom: 8 }} />
							{a.showProgress && (
								<div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 8 }}>
									<ShimmerBar height={12} width={36} />
									<div style={{ flex: 1, height: 3, background: '#e8eaee' }}>
										<div style={{ height: '100%', width: `${30 + i * 25}%`, background: accent }} />
									</div>
								</div>
							)}
							{a.showMeta && (
								<div style={{ display: 'flex', gap: 16 }}>
									<ShimmerBar height={12} width={70} />
									<ShimmerBar height={12} width={60} />
									<ShimmerBar height={12} width={50} />
								</div>
							)}
						</div>
					</div>
				))}
			</div>
		);

		const renderCard = (campaign, idx) => {
			const hasThumb = campaign.thumbnail && campaign.thumbnail.length > 0;
			const excerpt = campaign.excerpt
				? stripHtml(campaign.excerpt).substring(0, 200) + (stripHtml(campaign.excerpt).length > 200 ? '…' : '')
				: '';
			const pct = campaign.percentage || 0;
			const raisedText = campaign.raised_formatted || '';
			const goalText = campaign.goal_formatted || '';
			let daysLeft = '';
			if (campaign.end_date) {
				const diff = Math.ceil((new Date(campaign.end_date) - new Date()) / (1000 * 60 * 60 * 24));
				if (diff > 0) daysLeft = String(diff);
			}

			return (
				<article key={campaign.id} className="gf-cl-card" style={{
					'--gf-cl-i': idx,
					display: 'flex', flexDirection: 'row-reverse',
					gap: 28, padding: '28px 0',
					borderTop: idx > 0 ? '1px solid #f0f0f0' : 'none',
					background: 'transparent',
				}}>
					{a.showImage && (() => {
						const tilts = [-1.4, 1.2, -1.0];
						const tiltDeg = tilts[idx % 3];
						return (
						<div style={{
							position: 'relative', flex: 'none', alignSelf: 'flex-start',
							width: 200, aspectRatio: imageRatio,
							transform: `rotate(${tiltDeg}deg)`,
						}}>
							<div style={{
								position: 'relative', zIndex: 2,
								background: '#fff', padding: '8px 8px 12px',
								boxShadow: '0 1px 2px rgba(17,17,17,0.07), 0 12px 28px -8px rgba(17,17,17,0.16)',
							}}>
								<a href={campaign.link || '#'} style={{ display: 'block' }}>
									{hasThumb ? (
										<img src={campaign.thumbnail} alt={campaign.title || ''}
											style={{ display: 'block', width: '100%', height: '100%', objectFit: 'cover', background: '#e8eaee' }} />
									) : (
										<svg viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice"
											style={{ display: 'block', width: '100%', height: '100%', background: '#e8eaee' }}>
											<rect width="400" height="300" fill="#e8eaee"/>
										</svg>
									)}
								</a>
								{hasThumb && (
									<small style={{
										display: 'block', margin: '10px 0 2px',
										fontSize: '0.78em', lineHeight: 1.25,
										textAlign: 'center', color: a.captionColor || '#4b5563',
									}} aria-hidden="true">
										{campaign.title || ''}
									</small>
								)}
							</div>
						</div>
					);
					})()}

					<div style={{ flex: 1, minWidth: 0, display: 'flex', flexDirection: 'column' }}>
						<span style={{
							display: 'block', marginBottom: 6,
							fontSize: '0.75em', textTransform: 'uppercase',
							letterSpacing: '0.07em', color: a.categoryColor || '#6b7280',
						}}>
							{__('Campaign', 'giftflow')}
						</span>

						<h4 style={{ margin: '0 0 6px', fontWeight: 600, lineHeight: 1.35 }}>
							<a href={campaign.link || '#'} style={{ textDecoration: 'none', color: '#111' }}>
								{campaign.title || __('Untitled Campaign', 'giftflow')}
							</a>
						</h4>

						{a.showExcerpt && excerpt && (
							<p style={{
								margin: '0 0 14px', whiteSpace: 'nowrap',
								overflow: 'hidden', textOverflow: 'ellipsis',
								color: '#111', opacity: 0.82,
							}}>
								{excerpt}
							</p>
						)}

						{a.showProgress && (
							<div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 12 }}>
								<span style={{ flex: 'none', color: '#111', fontVariantNumeric: 'tabular-nums' }}>
									{pct}%
								</span>
								<div style={{ position: 'relative', flex: 1, height: 3, background: '#e8eaee' }}>
									<span style={{
										position: 'absolute', inset: '0 auto 0 0',
										width: pct + '%', background: accent,
									}} />
								</div>
							</div>
						)}

						{a.showMeta && (
							<div style={{
								display: 'flex', flexWrap: 'wrap',
								alignItems: 'baseline',
								margin: 0, padding: 0, listStyle: 'none',
								color: '#999',
							}}>
								<span style={{ display: 'inline-flex', alignItems: 'baseline' }}>
									<strong style={{ fontWeight: 650, color: '#111' }}>{raisedText}</strong>&nbsp;{__('raised', 'giftflow')}
								</span>
								{goalText && (<>
									<span style={{ margin: '0 9px', color: '#d9d9d9' }}>·</span>
									<span style={{ display: 'inline-flex', alignItems: 'baseline' }}>
										{__('of', 'giftflow')}&nbsp;<strong style={{ fontWeight: 650, color: '#111' }}>{goalText}</strong>&nbsp;{__('goal', 'giftflow')}
									</span>
								</>)}
								{daysLeft && (<>
									<span style={{ margin: '0 9px', color: '#d9d9d9' }}>·</span>
									<span style={{ display: 'inline-flex', alignItems: 'baseline' }}>
										<strong style={{ fontWeight: 650, color: '#111' }}>{daysLeft} {daysLeft === '1' ? __('day', 'giftflow') : __('days', 'giftflow')}</strong>&nbsp;{__('left', 'giftflow')}
									</span>
								</>)}
								{campaign.location && (<>
									<span style={{ margin: '0 9px', color: '#d9d9d9' }}>·</span>
									<span style={{ display: 'inline-flex', alignItems: 'baseline', gap: 4 }}>
										<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
										{campaign.location}
									</span>
								</>)}
							</div>
						)}
					</div>
				</article>
			);
		};

		const lb = { marginBottom: 6, fontWeight: 500, textTransform: 'uppercase', color: '#757575' };

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Campaign', 'giftflow')} initialOpen={true}>
						<CampaignSelector
							value={a.campaignId || 0}
							onChange={(v) => setAttributes({ campaignId: v })}
							help={__('Set to 0 to show all campaigns.', 'giftflow')}
						/>
					</PanelBody>
					<PanelBody title={__('Query', 'giftflow')} initialOpen={campaignId === 0}>
						{!campaignId && (<>
							<RangeControl label={__('Campaigns to show', 'giftflow')} value={a.perPage || 6} onChange={(v) => setAttributes({ perPage: v || 6 })} min={1} max={24} />
							<SelectControl label={__('Order by', 'giftflow')} value={a.orderby || 'date'}
								options={[
									{ label: __('Date', 'giftflow'), value: 'date' },
									{ label: __('Title', 'giftflow'), value: 'title' },
									{ label: __('Modified', 'giftflow'), value: 'modified' },
								]} onChange={(v) => setAttributes({ orderby: v })} />
							<SelectControl label={__('Order', 'giftflow')} value={a.order || 'DESC'}
								options={[
									{ label: __('Newest first', 'giftflow'), value: 'DESC' },
									{ label: __('Oldest first', 'giftflow'), value: 'ASC' },
								]} onChange={(v) => setAttributes({ order: v })} />
							<SelectControl label={__('Filter by category', 'giftflow')} value={a.category || ''} options={catOpts} onChange={(v) => setAttributes({ category: v })} />
						</>)}
					</PanelBody>
					<PanelBody title={__('Layout', 'giftflow')} initialOpen={false}>
						<SelectControl label={__('Image ratio', 'giftflow')} value={a.imageRatio || '4/3'}
							options={[
								{ label: __('Landscape 4:3', 'giftflow'), value: '4/3' },
								{ label: __('Widescreen 16:9', 'giftflow'), value: '16/9' },
								{ label: __('Square 1:1', 'giftflow'), value: '1/1' },
								{ label: __('Classic 3:2', 'giftflow'), value: '3/2' },
								{ label: __('Auto (from image)', 'giftflow'), value: 'auto' },
							]} onChange={(v) => setAttributes({ imageRatio: v })} />
					</PanelBody>
					<PanelBody title={__('Content', 'giftflow')} initialOpen={false}>
						<ToggleControl label={__('Show image', 'giftflow')} checked={a.showImage !== false} onChange={(v) => setAttributes({ showImage: v })} />
						<ToggleControl label={__('Show excerpt', 'giftflow')} checked={a.showExcerpt !== false} onChange={(v) => setAttributes({ showExcerpt: v })} />
						<ToggleControl label={__('Show progress', 'giftflow')} checked={a.showProgress !== false} onChange={(v) => setAttributes({ showProgress: v })} />
						<ToggleControl label={__('Show meta', 'giftflow')} checked={a.showMeta !== false} onChange={(v) => setAttributes({ showMeta: v })} />
						<ToggleControl label={__('Show pagination', 'giftflow')} checked={a.showPagination !== false} onChange={(v) => setAttributes({ showPagination: v })} />
					</PanelBody>
					<PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
						<BaseControl label={__('Accent color', 'giftflow')}>
							<ColorPalette value={a.accentColor} onChange={(v) => setAttributes({ accentColor: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
						<BaseControl label={__('Card background', 'giftflow')}>
							<ColorPalette value={a.cardBackground} onChange={(v) => setAttributes({ cardBackground: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
						<BaseControl label={__('Title color', 'giftflow')}>
							<ColorPalette value={a.titleColor} onChange={(v) => setAttributes({ titleColor: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
						<BaseControl label={__('Category color', 'giftflow')}>
							<ColorPalette value={a.categoryColor} onChange={(v) => setAttributes({ categoryColor: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
						<BaseControl label={__('Meta color', 'giftflow')}>
							<ColorPalette value={a.metaColor} onChange={(v) => setAttributes({ metaColor: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
						<BaseControl label={__('Caption color', 'giftflow')}>
							<ColorPalette value={a.captionColor} onChange={(v) => setAttributes({ captionColor: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
					</PanelBody>
				</InspectorControls>

				<div {...blockProps}>
					{shimmer ? renderSkeleton() : campaigns && campaigns.length > 0 ? (
						<div className="gf-cl-list">
							{campaigns.map((c, i) => renderCard(c, i))}
						</div>
					) : (
						<div style={{ padding: '40px 0', textAlign: 'center', color: '#999' }}>
							{__('No campaigns found.', 'giftflow')}
						</div>
					)}
				</div>
			</>
		);
	},
	save: () => null,
});
