import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	TextControl,
	BaseControl,
	ColorPalette,
	RangeControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ShimmerBox, ShimmerBar, ensureShimmerStyles, useCampaignSelector } from '../_editor-utils';

registerBlockType('giftflow/campaign-card', {
	apiVersion: 3,
	title: __('Campaign Card', 'giftflow'),
	icon: 'star-filled',
	category: 'giftflow',
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const a = attributes;
		const accent = a.accentColor || '#2563eb';
		const cardStyle = a.cardStyle || 'classic';
		const showImage = a.showImage !== false;
		const showExcerpt = a.showExcerpt !== false;
		const showProgress = a.showProgress !== false;
		const showPresets = a.showPresetAmounts === true;
		const showButton = a.showButton !== false;
		const btnStyle = a.buttonStyle || 'filled';
		const btnFullWidth = a.buttonFullWidth === true;
		const overlayOpacity = a.overlayOpacity || 60;
		const isOverlay = cardStyle === 'overlay';

		const blockProps = useBlockProps({
			className: `giftflow-campaign-card giftflow-campaign-card--${cardStyle}`,
			style: {
				'--gf-cc-accent': accent,
				'--gf-cc-accent-glow': accent + '2e',
				'--gf-cc-accent-soft': accent + '14',
				'--gf-cc-overlay-opacity': overlayOpacity / 100,
				'--gf-cc-radius': '22px',
				'--gf-cc-inner-radius': '14px',
				minHeight: isOverlay ? 440 : undefined,
			},
		});
		ensureShimmerStyles();

		const { CampaignSelector } = useCampaignSelector({ defaultLabel: __('Select a campaign…', 'giftflow') });

		const labelStyle = { marginBottom: 6, fontSize: 11, fontWeight: 500, textTransform: 'uppercase', color: '#757575' };

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Campaign', 'giftflow')} initialOpen={true}>
						<CampaignSelector
							value={a.campaignId || 0}
							onChange={(v) => setAttributes({ campaignId: v })}
						/>
					</PanelBody>
					<PanelBody title={__('Layout', 'giftflow')} initialOpen={false}>
						<div style={{ marginBottom: 16 }}>
							<div style={labelStyle}>{__('Card style', 'giftflow')}</div>
							<ToggleGroupControl value={cardStyle} onChange={(v) => setAttributes({ cardStyle: v })} isBlock __nextHasNoMarginBottom>
								<ToggleGroupControlOption value="classic" label={__('Classic', 'giftflow')} />
								<ToggleGroupControlOption value="overlay" label={__('Overlay', 'giftflow')} />
							</ToggleGroupControl>
						</div>
						{isOverlay && (
							<RangeControl
								label={__('Overlay opacity', 'giftflow')}
								value={overlayOpacity}
								onChange={(v) => setAttributes({ overlayOpacity: v })}
								min={10}
								max={90}
								step={5}
							/>
						)}
						<ToggleControl label={__('Show image', 'giftflow')} checked={showImage} onChange={(v) => setAttributes({ showImage: v })} />
						<ToggleControl label={__('Show excerpt', 'giftflow')} checked={showExcerpt} onChange={(v) => setAttributes({ showExcerpt: v })} />
						<ToggleControl label={__('Show progress', 'giftflow')} checked={showProgress} onChange={(v) => setAttributes({ showProgress: v })} />
						<ToggleControl label={__('Show preset amounts', 'giftflow')} checked={showPresets} onChange={(v) => setAttributes({ showPresetAmounts: v })} />
						<ToggleControl label={__('Show donate button', 'giftflow')} checked={showButton} onChange={(v) => setAttributes({ showButton: v })} />
						{showButton && (
							<TextControl
								label={__('Button text', 'giftflow')}
								value={a.buttonText || ''}
								onChange={(v) => setAttributes({ buttonText: v })}
								placeholder={__('Donate Now', 'giftflow')}
							/>
						)}
					</PanelBody>
					<PanelBody title={__('Style', 'giftflow')} initialOpen={false}>
						{showButton && (
							<>
								<div style={{ marginBottom: 16 }}>
									<div style={labelStyle}>{__('Button style', 'giftflow')}</div>
									<ToggleGroupControl value={btnStyle} onChange={(v) => setAttributes({ buttonStyle: v })} isBlock __nextHasNoMarginBottom>
										<ToggleGroupControlOption value="filled" label={__('Filled', 'giftflow')} />
										<ToggleGroupControlOption value="outline" label={__('Outline', 'giftflow')} />
									</ToggleGroupControl>
								</div>
								<ToggleControl label={__('Full width', 'giftflow')} checked={btnFullWidth} onChange={(v) => setAttributes({ buttonFullWidth: v })} />
							</>
						)}
						<div style={{ marginTop: showButton ? 16 : 0 }}>
							<BaseControl label={__('Accent color', 'giftflow')}>
								<ColorPalette
									value={a.accentColor}
									onChange={(v) => setAttributes({ accentColor: v || '' })}
									disableCustomColors={false}
									clearable={true}
								/>
							</BaseControl>
						</div>
					</PanelBody>
				</InspectorControls>

				<div {...blockProps}>
					{/* Media */}
					{showImage && (
						<div
							className="giftflow-campaign-card__media"
							style={
								isOverlay
									? { position: 'absolute', inset: 0, zIndex: 0 }
									: { aspectRatio: '16/10', margin: '1.25rem 1.25rem 0 1.25rem', borderRadius: 14 }
							}
						>
							<ShimmerBox height="100%" style={{ position: 'absolute', inset: 0, borderRadius: 0 }} />
							{isOverlay && (
								<div
									className="giftflow-campaign-card__overlay"
									style={{
										position: 'absolute',
										inset: 0,
										background: `linear-gradient(to top, rgba(0,0,0,${(overlayOpacity / 100) * 1.1}) 0%, rgba(0,0,0,${(overlayOpacity / 100) * 0.5}) 50%, transparent 100%)`,
									}}
								/>
							)}
						</div>
					)}

					{/* Content */}
					<div
						className="giftflow-campaign-card__content"
						style={
							isOverlay
								? {
										position: 'relative',
										zIndex: 1,
										marginTop: 'auto',
										background: 'rgba(0,0,0,0.25)',
										backdropFilter: 'blur(20px)',
										margin: '1rem',
										borderRadius: 14,
										padding: '1.5rem',
										color: '#fff',
								  }
								: {}
						}
					>
						{/* Category pill */}
						<span
							className="giftflow-campaign-card__category"
							style={{
								display: 'inline-flex',
								alignSelf: 'flex-start',
								padding: '4px 10px',
								borderRadius: 99,
								fontSize: 11,
								fontWeight: 700,
								textTransform: 'uppercase',
								backgroundColor: isOverlay ? 'rgba(255,255,255,0.15)' : accent + '14',
								color: isOverlay ? 'rgba(255,255,255,0.9)' : accent,
							}}
						>
							<ShimmerBar height={10} width={70} />
						</span>

						{/* Title */}
						<h3 className="giftflow-campaign-card__title">
							<ShimmerBar height={22} width="65%" />
						</h3>

						{/* Location */}
						<span className="giftflow-campaign-card__location" style={{ display: 'inline-flex', alignItems: 'center', gap: 5, fontSize: 12, fontWeight: 500, color: isOverlay ? 'rgba(255,255,255,0.7)' : '#6e6e73', marginTop: -2 }}>
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
							<ShimmerBar height={12} width={100} />
						</span>

						{/* Excerpt */}
						{showExcerpt && (
							<>
								<ShimmerBar height={13} width="95%" />
								<ShimmerBar height={13} width="70%" style={{ marginBottom: 12 }} />
							</>
						)}

						{/* Progress ring */}
						{showProgress && (
							<div className="giftflow-campaign-card__progress" style={{ display: 'flex', alignItems: 'center', gap: 20 }}>
								<div className="giftflow-campaign-card__progress-ring" style={{ position: 'relative', flexShrink: 0, width: 84, height: 84 }}>
									<svg viewBox="0 0 88 88" style={{ width: '100%', height: '100%' }}>
										<defs>
											<linearGradient id="gf-cc-ring-grad" x1="0%" y1="0%" x2="100%" y2="100%">
												<stop offset="0%" stopColor={accent} />
												<stop offset="100%" stopColor={accent + '99'} />
											</linearGradient>
										</defs>
										<circle cx="42" cy="42" r="36" fill="none" stroke={isOverlay ? 'rgba(255,255,255,0.15)' : '#e5e5ea'} strokeWidth="5" />
										<circle
											cx="42" cy="42" r="36"
											fill="none"
											stroke="url(#gf-cc-ring-grad)"
											strokeWidth="5"
											strokeLinecap="round"
											strokeDasharray={2 * Math.PI * 36}
											strokeDashoffset={2 * Math.PI * 36 * 0.58}
											style={{ transform: 'rotate(-90deg)', transformOrigin: '42px 42px' }}
										/>
									</svg>
									<div style={{ position: 'absolute', inset: 0, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', textAlign: 'center', lineHeight: 1.1 }}>
										<span style={{ fontSize: 18, fontWeight: 700, color: isOverlay ? '#fff' : '#1d1d1f', letterSpacing: '-0.02em' }}>42%</span>
										<span style={{ fontSize: 9, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.04em', color: isOverlay ? 'rgba(255,255,255,0.7)' : '#8e8e93' }}>{__('funded', 'giftflow')}</span>
									</div>
								</div>

								<div className="giftflow-campaign-card__progress-stats" style={{ flex: 1, display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 6, minWidth: 0 }}>
									{['$4,200', 'raised', '$10,000', 'goal', '12', 'donors', '18', 'days'].reduce((rows, _, i, arr) => {
										if (i % 2 === 0) {
											rows.push(
												<div key={i} style={{ display: 'flex', flexDirection: 'row', alignItems: 'baseline', justifyContent: 'center', gap: 5, padding: '7px 8px', borderRadius: 10, background: isOverlay ? 'rgba(255,255,255,0.1)' : '#f2f2f7' }}>
													<span style={{ fontSize: 15, fontWeight: 700, color: isOverlay ? '#fff' : '#1d1d1f', lineHeight: 1.3 }}>{arr[i]}</span>
													<span style={{ fontSize: 12, fontWeight: 500, color: isOverlay ? 'rgba(255,255,255,0.7)' : '#6e6e73', lineHeight: 1.3 }}>{arr[i + 1]}</span>
												</div>
											);
										}
										return rows;
									}, [])}
								</div>
							</div>
						)}

						{/* Divider + Actions */}
						{(showPresets || showButton) && (
							<>
								<hr style={{ height: 1, border: 'none', background: isOverlay ? 'rgba(255,255,255,0.12)' : '#e5e5ea', margin: '4px 0' }} />
								<div className="giftflow-campaign-card__actions" style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
									{showPresets && (
										<div className="giftflow-campaign-card__presets" style={{ display: 'flex', flexWrap: 'wrap', gap: 7 }}>
											{['$10', '$25', '$50', '$100'].map((label, i) => (
												<span
													key={i}
													className="giftflow-campaign-card__preset"
													style={{
														minWidth: 52,
														padding: '7px 14px',
														fontSize: 13,
														fontWeight: 600,
														borderRadius: 10,
														background: isOverlay ? 'rgba(255,255,255,0.14)' : '#f2f2f7',
														color: isOverlay ? 'rgba(255,255,255,0.9)' : '#1d1d1f',
														cursor: 'default',
														whiteSpace: 'nowrap',
														textAlign: 'center',
													}}
												>
													{label}
												</span>
											))}
										</div>
									)}
									{showButton && (
										<span
											className={`giftflow-campaign-card__button giftflow-campaign-card__button--${btnStyle}${btnFullWidth ? ' giftflow-campaign-card__button--full' : ''}`}
											style={{
												display: 'inline-flex',
												alignItems: 'center',
												justifyContent: 'center',
												gap: 8,
												width: '100%',
												padding: '14px 24px',
												borderRadius: 14,
												fontWeight: 650,
												...(btnStyle === 'filled'
													? {
															background: isOverlay ? '#fff' : accent,
															color: isOverlay ? '#1d1d1f' : '#fff',
															border: 'none',
													  }
													: {
															background: 'transparent',
															color: isOverlay ? '#fff' : accent,
															border: isOverlay ? '1.5px solid rgba(255,255,255,0.5)' : `1.5px solid ${accent}`,
													  }),
											}}
										>
											<span>{a.buttonText || __('Donate Now', 'giftflow')}</span>
											<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
										</span>
									)}
								</div>
							</>
						)}
					</div>
				</div>
			</>
		);
	},
	save: () => null,
});
