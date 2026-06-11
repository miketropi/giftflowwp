import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	ToggleControl,
	RangeControl,
	Button,
	Modal,
	ColorPalette,
	BaseControl,
	ResponsiveWrapper,
} from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

let gid = Date.now();

function newStat() {
	return { _id: String( ++gid ), value: '', label: '' };
}

function newMetric() {
	return { _id: String( ++gid ), value: '', label: '' };
}

function newTestimonial() {
	return {
		_id: String( ++gid ),
		sponsorName: '',
		color: '#6366f1',
		sponsorImage: null,
		impactBadge: '',
		quote: '',
		metrics: [],
		authorName: '',
		authorRole: '',
		authorImage: null,
		stars: 5,
	};
}

registerBlockType( 'giftflow/sponsors-testimonials-carousel', {
	apiVersion: 3,
	title: __( 'Sponsors Testimonials Carousel', 'giftflow' ),
	icon: 'star-filled',
	category: 'giftflow',
	attributes: {
		eyebrowText: { type: 'string', default: __( 'Trusted Partners', 'giftflow' ) },
		heading: { type: 'string', default: __( 'Real stories from real partners', 'giftflow' ) },
		subheading: { type: 'string', default: '' },
		showStats: { type: 'boolean', default: true },
		stats: { type: 'array', default: [ { value: '$4.2M+', label: __( 'Donations', 'giftflow' ) }, { value: '180+', label: __( 'Partners', 'giftflow' ) }, { value: '98%', label: __( 'Satisfaction', 'giftflow' ) } ] },
		testimonials: { type: 'array', default: [] },
		autoplay: { type: 'boolean', default: true },
		autoplayDelay: { type: 'number', default: 4000 },
		loop: { type: 'boolean', default: true },
		columns: { type: 'integer', default: 3 },
	},
	edit: ( props ) => {
		const { attributes, setAttributes } = props;
		const a = attributes;
		const testimonials = a.testimonials || [];
		const stats = a.stats || [];
		const columns = a.columns || 3;
		const blockProps = useBlockProps( { className: 'giftflow-sponsors-testimonials-carousel' } );

		const [ editingIdx, setEditingIdx ] = useState( null );
		const editingItem = editingIdx !== null ? ( testimonials[ editingIdx ] || null ) : null;

		const updateTestimonial = ( idx, patch ) => {
			const next = [ ...testimonials ];
			next[ idx ] = { ...next[ idx ], ...patch };
			setAttributes( { testimonials: next } );
		};

		const removeTestimonial = ( idx ) => {
			const next = testimonials.filter( ( _, i ) => i !== idx );
			setEditingIdx( null );
			setAttributes( { testimonials: next } );
		};

		const addTestimonial = () => {
			const item = newTestimonial();
			setAttributes( { testimonials: [ ...testimonials, item ] } );
			setEditingIdx( testimonials.length );
		};

		const cloneTestimonial = ( idx ) => {
			const clone = { ...testimonials[ idx ], _id: String( ++gid ) };
			const next = [ ...testimonials ];
			next.splice( idx + 1, 0, clone );
			setAttributes( { testimonials: next } );
		};

		const rowStyle = { display: 'flex', alignItems: 'flex-start', gap: 6, marginBottom: 4 };
		const rowInputStyle = { flex: 1 };

		const displayItems = testimonials.length > 0 ? testimonials : [ { _id: 'empty' } ];
		const cardW = columns > 1 ? `calc(${ 100 / columns }% - ${ ( ( columns - 1 ) * 16 ) / columns }px)` : '100%';

		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Header', 'giftflow' ) } initialOpen={ true }>
						<TextControl label={ __( 'Eyebrow', 'giftflow' ) } value={ a.eyebrowText } onChange={ ( v ) => setAttributes( { eyebrowText: v } ) } __nextHasNoMarginBottom />
						<TextControl label={ __( 'Heading', 'giftflow' ) } value={ a.heading } onChange={ ( v ) => setAttributes( { heading: v } ) } __nextHasNoMarginBottom />
						<TextareaControl label={ __( 'Subheading', 'giftflow' ) } value={ a.subheading } onChange={ ( v ) => setAttributes( { subheading: v } ) } __nextHasNoMarginBottom />
						<ToggleControl label={ __( 'Show stats', 'giftflow' ) } checked={ a.showStats } onChange={ ( v ) => setAttributes( { showStats: v } ) } __nextHasNoMarginBottom />
						{ a.showStats && (
							<div style={ { marginTop: 8 } }>
								{ stats.map( ( s, i ) => (
									<div key={ s._id || i } style={ rowStyle }>
										<TextControl
											placeholder={ __( 'Value', 'giftflow' ) }
											value={ s.value || '' }
											onChange={ ( v ) => {
												const next = [ ...stats ];
												next[ i ] = { ...next[ i ], value: v };
												setAttributes( { stats: next } );
											} }
											style={ rowInputStyle }
											__nextHasNoMarginBottom
										/>
										<TextControl
											placeholder={ __( 'Label', 'giftflow' ) }
											value={ s.label || '' }
											onChange={ ( v ) => {
												const next = [ ...stats ];
												next[ i ] = { ...next[ i ], label: v };
												setAttributes( { stats: next } );
											} }
											style={ rowInputStyle }
											__nextHasNoMarginBottom
										/>
										<Button
											icon="no-alt"
											isSmall
											isDestructive
											label={ __( 'Remove', 'giftflow' ) }
											onClick={ () => setAttributes( { stats: stats.filter( ( _, j ) => j !== i ) } ) }
											style={ { marginTop: 22 } }
										/>
									</div>
								) ) }
								<Button
									variant="secondary"
									isSmall
									onClick={ () => setAttributes( { stats: [ ...stats, newStat() ] } ) }
									style={ { marginTop: 4 } }
								>
									{ __( '+ Add Stat', 'giftflow' ) }
								</Button>
							</div>
						) }
					</PanelBody>

					<PanelBody title={ __( 'Testimonials', 'giftflow' ) } initialOpen={ true }>
						{ testimonials.length === 0 && (
							<p style={ { color: '#757575', fontSize: 12, marginBottom: 12 } }>
								{ __( 'No items added. Default sample data will appear on the frontend.', 'giftflow' ) }
							</p>
						) }
						{ testimonials.map( ( item, idx ) => (
							<div key={ item._id || idx } style={ { display: 'flex', alignItems: 'center', gap: 8, padding: '6px 8px', marginBottom: 4, border: '1px solid #e0e0e0', borderRadius: 4 } }>
								<span style={ { flex: 1, fontSize: 12, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' } }>
									{ item.sponsorName || sprintf( __( 'Item %d', 'giftflow' ), idx + 1 ) }
								</span>
								<Button icon="edit" isSmall label={ __( 'Edit', 'giftflow' ) } onClick={ () => setEditingIdx( idx ) } />
								<Button icon="admin-page" isSmall label={ __( 'Clone', 'giftflow' ) } onClick={ () => cloneTestimonial( idx ) } />
								<Button icon="no-alt" isSmall isDestructive label={ __( 'Remove', 'giftflow' ) } onClick={ () => removeTestimonial( idx ) } />
							</div>
						) ) }
						<Button variant="secondary" onClick={ addTestimonial } style={ { width: '100%', justifyContent: 'center', marginTop: 8 } }>
							{ __( '+ Add Testimonial', 'giftflow' ) }
						</Button>
					</PanelBody>

					<PanelBody title={ __( 'Carousel', 'giftflow' ) } initialOpen={ false }>
						<RangeControl label={ __( 'Columns', 'giftflow' ) } value={ columns } onChange={ ( v ) => setAttributes( { columns: v } ) } min={ 1 } max={ 5 } />
						<ToggleControl label={ __( 'Autoplay', 'giftflow' ) } checked={ a.autoplay } onChange={ ( v ) => setAttributes( { autoplay: v } ) } __nextHasNoMarginBottom />
						{ a.autoplay && ( <RangeControl label={ __( 'Delay (ms)', 'giftflow' ) } value={ a.autoplayDelay } onChange={ ( v ) => setAttributes( { autoplayDelay: v } ) } min={ 1000 } max={ 10000 } step={ 500 } /> ) }
						<ToggleControl label={ __( 'Loop', 'giftflow' ) } checked={ a.loop } onChange={ ( v ) => setAttributes( { loop: v } ) } __nextHasNoMarginBottom />
					</PanelBody>
				</InspectorControls>

				<div { ...blockProps }>
					<header className="giftflow-st-carousel__header">
						{ a.eyebrowText && (
							<span className="giftflow-st-carousel__eyebrow">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
									<path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
								</svg>
								{ a.eyebrowText }
							</span>
						) }
						<h2 className="giftflow-st-carousel__title">{ a.heading }</h2>
						{ a.subheading && <p className="giftflow-st-carousel__subtitle">{ a.subheading }</p> }
						{ a.showStats && stats.length > 0 && (
							<div className="giftflow-st-carousel__stats">
								{ stats.map( ( s, i ) => (
									<div className="giftflow-st-carousel__stat" key={ i }>
										<span className="giftflow-st-carousel__stat-value">{ s.value || '--' }</span>
										<span className="giftflow-st-carousel__stat-label">{ s.label || '--' }</span>
									</div>
								) ) }
							</div>
						) }
					</header>

					<div style={ { overflowX: 'auto', paddingBottom: 12 } }>
						<div style={ { display: 'flex', gap: 16 } }>
							{ displayItems.slice( 0, Math.max( columns, displayItems.length ) ).map( ( item, i ) => {
								const isEmpty = testimonials.length === 0;
								const initials = ( item.sponsorName || '' ).charAt( 0 ).toUpperCase() || '?';
								const itemColor = item.color || '#6366f1';
								const itemMetrics = item.metrics && item.metrics.length ? item.metrics : [ {}, {}, {} ];
								return (
									<div key={ item._id || i } className="giftflow-st-carousel__card" style={ { flex: `0 0 ${ cardW }`, minWidth: 240 } }>
										<div className="giftflow-st-carousel__card-header">
											<div className="giftflow-st-carousel__sponsor">
												<div className="giftflow-st-carousel__sponsor-icon" style={ { background: itemColor } }>
													{ ( item.sponsorImage && item.sponsorImage.url ) ? (
														<img src={ item.sponsorImage.url } alt="" style={ { width: '100%', height: '100%', objectFit: 'cover' } } />
													) : ( isEmpty ? '?' : initials ) }
												</div>
												<div className="giftflow-st-carousel__sponsor-info">
													<span className="giftflow-st-carousel__sponsor-name">{ isEmpty ? __( 'Sponsor', 'giftflow' ) : ( item.sponsorName || __( 'Untitled', 'giftflow' ) ) }</span>
												</div>
											</div>
											<div className="giftflow-st-carousel__badge" style={ { '--gf-st-item-color': itemColor } }>
												<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12" /></svg>
												{ isEmpty ? '+65%' : ( item.impactBadge || '--' ) }
											</div>
										</div>
										<blockquote className="giftflow-st-carousel__quote" style={ { paddingLeft: '1.25rem' } }>
											<p>{ isEmpty ? __( 'Testimonial quote appears here...', 'giftflow' ) : ( item.quote || __( 'No quote provided.', 'giftflow' ) ) }</p>
										</blockquote>
										<div className="giftflow-st-carousel__metrics" style={ { '--gf-st-item-color': itemColor } }>
											{ itemMetrics.map( ( m, j ) => (
												<div className="giftflow-st-carousel__metric" key={ j }>
													<span className="giftflow-st-carousel__metric-value">{ isEmpty ? [ '$380K', '2,400', '45' ][ j ] : ( m.value || '--' ) }</span>
													<span className="giftflow-st-carousel__metric-label">{ isEmpty ? [ __( 'Annual', 'giftflow' ), __( 'Donors', 'giftflow' ), __( 'Campaigns', 'giftflow' ) ][ j ] : ( m.label || '--' ) }</span>
												</div>
											) ) }
										</div>
										<div className="giftflow-st-carousel__author">
											<div className="giftflow-st-carousel__author-avatar" style={ { background: itemColor } }>
												{ ( item.authorImage && item.authorImage.url ) ? (
													<img src={ item.authorImage.url } alt="" style={ { width: '100%', height: '100%', objectFit: 'cover' } } />
												) : ( isEmpty ? '?' : initials ) }
											</div>
											<div className="giftflow-st-carousel__author-info">
												<span className="giftflow-st-carousel__author-name">{ isEmpty ? __( 'Author', 'giftflow' ) : ( item.authorName || __( 'Author', 'giftflow' ) ) }</span>
												<span className="giftflow-st-carousel__author-role">{ isEmpty ? __( 'Role', 'giftflow' ) : ( item.authorRole || __( 'Role', 'giftflow' ) ) }</span>
											</div>
											<div className="giftflow-st-carousel__stars" style={ { gap: 2 } }>
												{ [ ...Array( isEmpty ? 5 : ( item.stars || 5 ) ) ].map( ( _, s ) => (
													<svg key={ s } width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" /></svg>
												) ) }
											</div>
										</div>
									</div>
								);
							} ) }
						</div>
					</div>

					<div style={ { display: 'flex', justifyContent: 'center', alignItems: 'center', gap: 8, marginTop: 16 } }>
						<div style={ { width: 36, height: 36, borderRadius: '50%', border: '1px solid #e2e8f0', display: 'flex', alignItems: 'center', justifyContent: 'center', opacity: 0.4, background: '#fff' } }>
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
						</div>
						<div style={ { display: 'flex', gap: 6 } }>
							<div style={ { width: 20, height: 8, borderRadius: 4, background: 'var(--gf-st-accent, #2563eb)' } } />
							{ displayItems.slice( 1 ).map( ( _, d ) => ( <div key={ d } style={ { width: 8, height: 8, borderRadius: '50%', background: '#e2e8f0' } } /> ) ) }
						</div>
						<div style={ { width: 36, height: 36, borderRadius: '50%', border: '1px solid #e2e8f0', display: 'flex', alignItems: 'center', justifyContent: 'center', opacity: 0.4, background: '#fff' } }>
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M9 18l6-6-6-6" /></svg>
						</div>
					</div>
				</div>

				{ editingItem && (
					<Modal
						title={ editingItem.sponsorName ? sprintf( __( 'Edit "%s"', 'giftflow' ), editingItem.sponsorName ) : __( 'Add Testimonial', 'giftflow' ) }
						onRequestClose={ () => setEditingIdx( null ) }
					>
						<div style={ { display: 'flex', flexDirection: 'column', gap: 12 } }>
							<div style={ { display: 'flex', gap: 12, alignItems: 'flex-start' } }>
								<div style={ { flex: 1 } }>
									<TextControl label={ __( 'Sponsor Name', 'giftflow' ) } value={ editingItem.sponsorName || '' } onChange={ ( v ) => updateTestimonial( editingIdx, { sponsorName: v } ) } />
								</div>
								<div style={ { flexShrink: 0, marginTop: 4 } }>
									<MediaUploadCheck>
										<MediaUpload
											onSelect={ ( media ) => updateTestimonial( editingIdx, { sponsorImage: { id: media.id, url: media.url, alt: media.alt || '' } } ) }
											allowedTypes={ [ 'image' ] }
											value={ editingItem.sponsorImage ? editingItem.sponsorImage.id : 0 }
											render={ ( { open } ) => (
												<div style={ { display: 'flex', gap: 6, alignItems: 'center' } }>
													<div style={ { width: 44, height: 44, borderRadius: 12, overflow: 'hidden', background: '#e5e7eb', flexShrink: 0 } }>
														{ editingItem.sponsorImage ? (
															<img src={ editingItem.sponsorImage.url } alt="" style={ { width: '100%', height: '100%', objectFit: 'cover' } } />
														) : (
															<div style={ { width: 44, height: 44, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, fontWeight: 700, color: '#fff', background: editingItem.color || '#6366f1', borderRadius: 12 } }>
																{ ( editingItem.sponsorName || '?' ).charAt( 0 ).toUpperCase() }
															</div>
														) }
													</div>
													<Button isSmall onClick={ open } variant="secondary">
														{ editingItem.sponsorImage ? __( 'Replace', 'giftflow' ) : __( 'Logo', 'giftflow' ) }
													</Button>
													{ editingItem.sponsorImage && (
														<Button isSmall isDestructive onClick={ () => updateTestimonial( editingIdx, { sponsorImage: null } ) }>
															{ __( 'Remove', 'giftflow' ) }
														</Button>
													) }
												</div>
											) }
										/>
									</MediaUploadCheck>
								</div>
							</div>
							<BaseControl label={ __( 'Accent Color', 'giftflow' ) } id="gf-st-color">
								<ColorPalette
									value={ editingItem.color || '#6366f1' }
									onChange={ ( v ) => updateTestimonial( editingIdx, { color: v || '#6366f1' } ) }
									disableCustomColors={ false }
								/>
							</BaseControl>
							<TextControl label={ __( 'Badge', 'giftflow' ) } value={ editingItem.impactBadge || '' } onChange={ ( v ) => updateTestimonial( editingIdx, { impactBadge: v } ) } help={ __( 'e.g. "+65%" or "2x donors"', 'giftflow' ) } />
							<TextareaControl label={ __( 'Quote', 'giftflow' ) } value={ editingItem.quote || '' } onChange={ ( v ) => updateTestimonial( editingIdx, { quote: v } ) } />

							<div>
								<div style={ { marginBottom: 6, fontSize: 11, fontWeight: 600, textTransform: 'uppercase' } }>
									{ __( 'Metrics', 'giftflow' ) }
								</div>
								{ ( editingItem.metrics || [] ).map( ( m, j ) => (
									<div key={ m._id || j } style={ rowStyle }>
										<TextControl
											placeholder={ __( 'Value', 'giftflow' ) }
											value={ m.value || '' }
											onChange={ ( v ) => {
												const next = [ ...( editingItem.metrics || [] ) ];
												next[ j ] = { ...next[ j ], value: v };
												updateTestimonial( editingIdx, { metrics: next } );
											} }
											style={ rowInputStyle }
											__nextHasNoMarginBottom
										/>
										<TextControl
											placeholder={ __( 'Label', 'giftflow' ) }
											value={ m.label || '' }
											onChange={ ( v ) => {
												const next = [ ...( editingItem.metrics || [] ) ];
												next[ j ] = { ...next[ j ], label: v };
												updateTestimonial( editingIdx, { metrics: next } );
											} }
											style={ rowInputStyle }
											__nextHasNoMarginBottom
										/>
										<Button
											icon="no-alt"
											isSmall
											isDestructive
											label={ __( 'Remove', 'giftflow' ) }
											onClick={ () => updateTestimonial( editingIdx, { metrics: editingItem.metrics.filter( ( _, k ) => k !== j ) } ) }
											style={ { marginTop: 22 } }
										/>
									</div>
								) ) }
								<Button
									variant="secondary"
									isSmall
									onClick={ () => updateTestimonial( editingIdx, { metrics: [ ...( editingItem.metrics || [] ), newMetric() ] } ) }
								>
									{ __( '+ Add Metric', 'giftflow' ) }
								</Button>
							</div>

							<TextControl label={ __( 'Author Name', 'giftflow' ) } value={ editingItem.authorName || '' } onChange={ ( v ) => updateTestimonial( editingIdx, { authorName: v } ) } />
							<TextControl label={ __( 'Author Role', 'giftflow' ) } value={ editingItem.authorRole || '' } onChange={ ( v ) => updateTestimonial( editingIdx, { authorRole: v } ) } />
							<div style={ { display: 'flex', gap: 6, alignItems: 'center' } }>
								<div style={ { width: 36, height: 36, borderRadius: '50%', overflow: 'hidden', background: '#e5e7eb', flexShrink: 0 } }>
									{ editingItem.authorImage ? (
										<img src={ editingItem.authorImage.url } alt="" style={ { width: '100%', height: '100%', objectFit: 'cover' } } />
									) : (
										<div style={ { width: 36, height: 36, display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 600, fontSize: 12, color: '#fff', background: editingItem.color || '#6366f1', borderRadius: '50%' } }>
											{ ( editingItem.authorName || '?' ).charAt( 0 ).toUpperCase() }
										</div>
									) }
								</div>
								<MediaUploadCheck>
									<MediaUpload
										onSelect={ ( media ) => updateTestimonial( editingIdx, { authorImage: { id: media.id, url: media.url, alt: media.alt || '' } } ) }
										allowedTypes={ [ 'image' ] }
										value={ editingItem.authorImage ? editingItem.authorImage.id : 0 }
										render={ ( { open } ) => (
											<Button isSmall onClick={ open } variant="secondary">
												{ editingItem.authorImage ? __( 'Replace Avatar', 'giftflow' ) : __( 'Avatar', 'giftflow' ) }
											</Button>
										) }
									/>
								</MediaUploadCheck>
								{ editingItem.authorImage && (
									<Button isSmall isDestructive onClick={ () => updateTestimonial( editingIdx, { authorImage: null } ) }>
										{ __( 'Remove', 'giftflow' ) }
									</Button>
								) }
							</div>
							<RangeControl label={ __( 'Star Rating', 'giftflow' ) } value={ editingItem.stars || 5 } onChange={ ( v ) => updateTestimonial( editingIdx, { stars: v } ) } min={ 1 } max={ 5 } />
						</div>
					</Modal>
				) }
			</>
		);
	},
} );
