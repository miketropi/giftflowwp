import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, TextareaControl, ToggleControl, __experimentalText as Text } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const PlusIcon = () => (
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" strokeWidth="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
);

const TrashIcon = () => (
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
);

const DEFAULT_FAQS = [
	{ question: __('How do I make a donation?', 'giftflow'), answer: __('Select a campaign, choose an amount, and complete the secure payment form. You can pay via credit card, PayPal, or bank transfer.', 'giftflow') },
	{ question: __('Is my donation tax-deductible?', 'giftflow'), answer: __('Yes, all donations are tax-deductible. You will receive a receipt via email for your records.', 'giftflow') },
	{ question: __('Can I set up a recurring donation?', 'giftflow'), answer: __('Absolutely. Choose the recurring option when making your donation to contribute monthly, quarterly, or yearly.', 'giftflow') },
	{ question: __('How do I track my donations?', 'giftflow'), answer: __('Create an account or log in to view your complete donation history, download receipts, and manage recurring contributions.', 'giftflow') },
];

const parseFaqs = (json) => {
	try {
		const arr = JSON.parse(json);
		return Array.isArray(arr) ? arr : [];
	} catch {
		return [];
	}
};

const toJson = (arr) => JSON.stringify(arr);

registerBlockType('giftflow/donation-faqs', {
	apiVersion: 3,
	title: __('Donation FAQs', 'giftflow'),
	icon: 'editor-help',
	category: 'giftflow',
	attributes: {
		faqsJson: { type: 'string', default: JSON.stringify(DEFAULT_FAQS) },
		openFirst: { type: 'boolean', default: false },
	},
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps({ className: 'giftflow-donation-faqs' });
		const faqs = parseFaqs(attributes.faqsJson);

		const addItem = () => {
			const updated = [...faqs, { question: '', answer: '' }];
			setAttributes({ faqsJson: toJson(updated) });
		};

		const removeItem = (index) => {
			const updated = faqs.filter((_, i) => i !== index);
			setAttributes({ faqsJson: toJson(updated) });
		};

		const updateItem = (index, field, value) => {
			const updated = faqs.map((item, i) => (i === index ? { ...item, [field]: value } : item));
			setAttributes({ faqsJson: toJson(updated) });
		};

		const displayFaqs = faqs.length > 0 ? faqs : DEFAULT_FAQS;

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('FAQ Items', 'giftflow')} initialOpen={true}>
						{faqs.map((item, idx) => (
							<div key={idx} style={{ marginBottom: 16, paddingBottom: 16, borderBottom: '1px solid #e5e7eb' }}>
								<div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 }}>
									<Text variant="label" isBlock>
										{__('FAQ', 'giftflow')} #{idx + 1}
									</Text>
								<Button
									icon={<TrashIcon />}
									isDestructive
									isSmall
									onClick={() => removeItem(idx)}
									label={__('Remove FAQ', 'giftflow')}
								/>
								</div>
								<TextControl
									label={__('Question', 'giftflow')}
									value={item.question || ''}
									onChange={(v) => updateItem(idx, 'question', v)}
								/>
								<TextareaControl
									label={__('Answer', 'giftflow')}
									value={item.answer || ''}
									onChange={(v) => updateItem(idx, 'answer', v)}
								/>
							</div>
						))}
					<Button
						icon={<PlusIcon />}
						variant="secondary"
						onClick={addItem}
						style={{ width: '100%', justifyContent: 'center' }}
					>
							{__('Add FAQ', 'giftflow')}
						</Button>
					</PanelBody>
					<PanelBody title={__('Settings', 'giftflow')}>
						<ToggleControl
							label={__('Open first item by default', 'giftflow')}
							checked={attributes.openFirst}
							onChange={(v) => setAttributes({ openFirst: v })}
						/>
					</PanelBody>
					<PanelBody title={__('About', 'giftflow')} initialOpen={false}>
						<p style={{ color: '#757575', fontSize: 13 }}>
							{__('Displays donation FAQs in an accordion layout. Add, edit, or reorder questions and answers via the sidebar.', 'giftflow')}
						</p>
					</PanelBody>
				</InspectorControls>
				<div {...blockProps}>
					{displayFaqs.map((faq, idx) => (
						<div key={idx} className={'giftflow-donation-faqs__item' + (idx === 0 && attributes.openFirst ? ' is-open' : '')}>
							<div className="giftflow-donation-faqs__question" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '1rem', padding: '1rem 0', borderBottom: '1px solid #e5e7eb', cursor: 'pointer' }}>
								<span style={{ flex: 1, fontWeight: 600 }}>{faq.question || __('(no question)', 'giftflow')}</span>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" width="18" height="18" style={{ flexShrink: 0, color: '#9ca3af' }}>
									<line x1="12" y1="5" x2="12" y2="19"></line>
									<line x1="5" y1="12" x2="19" y2="12"></line>
								</svg>
							</div>
							<div style={{ paddingBottom: '1rem', color: '#6b7280' }}>
								{faq.answer || __('(no answer)', 'giftflow')}
							</div>
						</div>
					))}
				</div>
			</>
		);
	},
});
