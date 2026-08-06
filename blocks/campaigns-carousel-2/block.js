import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	ToggleControl,
	TextControl,
	ColorPalette,
	BaseControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { ShimmerBox, ShimmerBar, ensureShimmerStyles } from '../_editor-utils';

registerBlockType('giftflow/campaigns-carousel-2', {
	apiVersion: 3,
	title: __('Campaign Carousel 2', 'giftflow'),
	icon: 'slides',
	category: 'giftflow',
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const a = attributes;
		const accent = a.accentColor || '#2563eb';

		const gap = a.gap || 24;
		const slidesPerView = a.slidesPerView || 3;

		const cssVars = {
			'--gf-cc-accent': accent,
			'--gf-cc-gap': gap + 'px',
			'--gf-cc-peek': String(Math.round(gap * 0.67)) + 'px',
		};
		if (a.cardBackground) cssVars['--gf-cc-card-bg'] = a.cardBackground;
		if (a.titleColor) cssVars['--gf-cc-title-color'] = a.titleColor;
		if (a.metaColor) cssVars['--gf-cc-meta-color'] = a.metaColor;
		if (a.categoryColor) cssVars['--gf-cc-category-color'] = a.categoryColor;

		const blockProps = useBlockProps({
			className: 'gf-cc',
			style: cssVars,
		});
		ensureShimmerStyles();

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
		}, [a.perPage, a.orderby, a.order, a.category]);

		const shimmer = isLoading || !campaigns;

		const tilts = ['-1.6deg', '1.4deg', '-1.1deg', '1.7deg'];

		const renderSkeleton = () => (
			<div className="gf-cc-viewport" style={{ overflow: 'auto', scrollbarWidth: 'none' }}>
				<div className="gf-cc-track" style={{ display: 'flex', gap: 24, padding: '6px 16px 30px 6px' }}>
					{[0, 1, 2, 3].map((i) => (
						<div key={i} style={{
							flex: '0 0 320px', minWidth: 0,
							background: '#fff', padding: '10px 10px 16px',
							boxShadow: '0 1px 2px rgba(17,17,17,0.07), 0 14px 30px -10px rgba(17,17,17,0.18)',
							transform: `rotate(${tilts[i]})`, transformOrigin: '50% 100%',
						}}>
							<div style={{ position: 'relative', overflow: 'hidden', aspectRatio: '4/3', background: '#e8eaee' }}>
								<ShimmerBox height="100%" style={{ position: 'absolute', inset: 0, borderRadius: 0 }} />
							</div>
							<ShimmerBar height={10} width="50%" style={{ margin: '12px auto 0', display: 'block' }} />
							<div style={{ paddingTop: 14, display: 'flex', flexDirection: 'column', gap: 10 }}>
								<ShimmerBar height={18} width="80%" />
								<div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
									<ShimmerBar height={12} width={36} />
									<div style={{ flex: 1, height: 3, background: '#e8eaee' }}>
										<div style={{ height: '100%', width: '40%', background: accent }} />
									</div>
								</div>
								<div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px 20px' }}>
									{['Raised', 'Goal', 'Days left', 'Location'].map((label) => (
										<div key={label}>
											<ShimmerBar height={8} width={50} />
											<ShimmerBar height={14} width={70} style={{ marginTop: 2 }} />
										</div>
									))}
								</div>
							</div>
						</div>
					))}
				</div>
			</div>
		);

		const renderCard = (campaign, idx) => {
			const hasThumb = campaign.thumbnail && campaign.thumbnail.length > 0;
			const pct = campaign.percentage || 0;
			const raisedText = campaign.raised_formatted || '';
			const goalText = campaign.goal_formatted || '';
			let daysLeft = '';
			if (campaign.end_date) {
				const diff = Math.ceil((new Date(campaign.end_date) - new Date()) / (1000 * 60 * 60 * 24));
				if (diff > 0) daysLeft = String(diff);
			}

			return (
				<div key={campaign.id} style={{
					flex: '0 0 320px', minWidth: 0,
					background: '#fff', padding: '10px 10px 16px',
					boxShadow: '0 1px 2px rgba(17,17,17,0.07), 0 14px 30px -10px rgba(17,17,17,0.18)',
					transform: `rotate(${tilts[idx % 4]})`, transformOrigin: '50% 100%',
				}}>
					<div style={{ position: 'relative', overflow: 'hidden', aspectRatio: '4/3', background: '#e8eaee' }}>
						{hasThumb ? (
							<img src={campaign.thumbnail} alt={campaign.title || ''}
								style={{ display: 'block', width: '100%', height: '100%', objectFit: 'cover' }} />
						) : (
							<svg viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice"
								style={{ display: 'block', width: '100%', height: '100%', background: '#e8eaee' }}>
								<rect width="400" height="300" fill="#e8eaee"/>
							</svg>
						)}
						<span style={{
							position: 'absolute', top: 8, left: 8,
							padding: '4px 10px', borderRadius: 999,
							background: 'rgba(255,255,255,0.85)',
							fontSize: '0.7em', textTransform: 'uppercase',
							letterSpacing: '0.08em', color: a.categoryColor || '#6b7280',
						}}>
							{__('Campaign', 'giftflow')}
						</span>
					</div>
					<small style={{
						display: 'block', margin: '12px 0 0',
						fontSize: '0.78em', lineHeight: 1.25,
						textAlign: 'center', color: '#4b5563',
					}}>
						{campaign.title || ''}
					</small>
					<div style={{ paddingTop: 14, display: 'flex', flexDirection: 'column', gap: 10 }}>
						<h4 style={{ margin: 0, fontWeight: 650, color: '#111' }}>
							<a href={campaign.link || '#'} style={{ textDecoration: 'none', color: 'inherit' }}>
								{campaign.title || __('Untitled Campaign', 'giftflow')}
							</a>
						</h4>
						{a.showProgress && (
							<div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
								<span style={{ flex: 'none', fontVariantNumeric: 'tabular-nums', color: '#111' }}>
									{pct}%
								</span>
								<div style={{ position: 'relative', flex: 1, height: 3, borderRadius: 2, background: '#e8eaee' }}>
									<span style={{
										position: 'absolute', inset: '0 auto 0 0',
										width: pct + '%', borderRadius: 2, background: accent,
									}} />
								</div>
							</div>
						)}
						{a.showMeta && (
							<div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px 20px' }}>
								<div>
									<span style={{ display: 'block', fontSize: '0.62em', textTransform: 'uppercase', letterSpacing: '0.08em', color: '#6b7280', marginBottom: 2 }}>
										{__('Raised', 'giftflow')}
									</span>
									<strong style={{ display: 'block', color: '#111' }}>{raisedText}</strong>
								</div>
								<div>
									<span style={{ display: 'block', fontSize: '0.62em', textTransform: 'uppercase', letterSpacing: '0.08em', color: '#6b7280', marginBottom: 2 }}>
										{__('Goal', 'giftflow')}
									</span>
									<strong style={{ display: 'block', color: '#111' }}>{goalText}</strong>
								</div>
								<div>
									<span style={{ display: 'block', fontSize: '0.62em', textTransform: 'uppercase', letterSpacing: '0.08em', color: '#6b7280', marginBottom: 2 }}>
										{__('Days left', 'giftflow')}
									</span>
									<strong style={{ display: 'block', color: '#111' }}>{daysLeft || '—'}</strong>
								</div>
								<div>
									<span style={{ display: 'block', fontSize: '0.62em', textTransform: 'uppercase', letterSpacing: '0.08em', color: '#6b7280', marginBottom: 2 }}>
										{__('Location', 'giftflow')}
									</span>
									<strong style={{ display: 'block', color: '#111' }}>
										{campaign.location ? (<>
											<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ width: '1.05em', height: '1.05em', marginRight: '0.4em', verticalAlign: '-0.14em' }}><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
											{campaign.location}
										</>) : '—'}
									</strong>
								</div>
							</div>
						)}
					</div>
				</div>
			);
		};

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Query', 'giftflow')} initialOpen={true}>
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
					</PanelBody>
					<PanelBody title={__('Content', 'giftflow')} initialOpen={false}>
						<TextControl label={__('Heading', 'giftflow')} value={a.heading || ''} onChange={(v) => setAttributes({ heading: v })} placeholder={__('Featured campaigns', 'giftflow')} />
						<RangeControl label={__('Gap between cards', 'giftflow')} value={gap} onChange={(v) => setAttributes({ gap: v })} min={8} max={48} step={4} />
						<RangeControl label={__('Slides per view', 'giftflow')} value={slidesPerView} onChange={(v) => setAttributes({ slidesPerView: v })} min={1} max={5} />
						<ToggleControl label={__('Show arrows', 'giftflow')} checked={a.showArrows !== false} onChange={(v) => setAttributes({ showArrows: v })} />
						<ToggleControl label={__('Show dots', 'giftflow')} checked={a.showDots !== false} onChange={(v) => setAttributes({ showDots: v })} />
						<ToggleControl label={__('Show progress', 'giftflow')} checked={a.showProgress !== false} onChange={(v) => setAttributes({ showProgress: v })} />
						<ToggleControl label={__('Show meta', 'giftflow')} checked={a.showMeta !== false} onChange={(v) => setAttributes({ showMeta: v })} />
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
						<BaseControl label={__('Meta color', 'giftflow')}>
							<ColorPalette value={a.metaColor} onChange={(v) => setAttributes({ metaColor: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
						<BaseControl label={__('Category color', 'giftflow')}>
							<ColorPalette value={a.categoryColor} onChange={(v) => setAttributes({ categoryColor: v || '' })} disableCustomColors={false} clearable={true} />
						</BaseControl>
					</PanelBody>
				</InspectorControls>

				<div {...blockProps}>
					<div style={{
						display: 'flex', alignItems: 'center', justifyContent: 'space-between',
						margin: '0 0 16px', paddingBottom: 16, gap: 16,
						borderBottom: '1px solid #e7e9ee',
					}}>
						<h3 style={{ margin: 0 }}>{a.heading || __('Featured campaigns', 'giftflow')}</h3>
					</div>
					<div style={{
						overflow: 'auto', scrollbarWidth: 'none', paddingBottom: 0,
					}}>
						{shimmer ? renderSkeleton() : campaigns && campaigns.length > 0 ? (
							<div style={{ display: 'flex', gap: gap, padding: '6px ' + Math.round(gap * 0.67) + 'px 30px 6px' }}>
								{campaigns.map((c, i) => renderCard(c, i))}
							</div>
						) : (
							<div style={{ padding: '40px 0', textAlign: 'center', color: '#999' }}>
								{__('No campaigns found.', 'giftflow')}
							</div>
						)}
					</div>
				</div>
			</>
		);
	},
	save: () => null,
});
