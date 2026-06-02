/**
 * GiftFlow Settings Page — Gutenberg-Style React App
 *
 * Uses @wordpress/components for a clean, modern settings experience
 * with a tabbed sidebar and live preview of inheritance.
 */
import { useState, useEffect, useCallback } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {
	Panel,
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	TextareaControl,
	ToggleControl,
	ColorPicker,
	RangeControl,
	Button,
	TabPanel,
	Notice,
	Spinner,
	Flex,
	FlexItem,
	Card,
	CardBody,
	CardHeader,
	__experimentalHeading as Heading,
	__experimentalVStack as VStack,
} from '@wordpress/components';

const SETTINGS_ENDPOINT = '/giftflow/v2/settings';

const TABS = [
	{ name: 'global', title: __('General', 'giftflow') },
	{ name: 'display', title: __('Display', 'giftflow') },
	{ name: 'email', title: __('Email', 'giftflow') },
	{ name: 'advanced', title: __('Advanced', 'giftflow') },
];

export default function GiftFlowSettingsPage() {
	const [settings, setSettings] = useState(null);
	const [defaults, setDefaults] = useState(null);
	const [saving, setSaving] = useState(false);
	const [notice, setNotice] = useState(null);

	useEffect(() => {
		apiFetch({ path: SETTINGS_ENDPOINT })
			.then(setSettings)
			.catch(() => setNotice({ type: 'error', message: __('Failed to load settings.', 'giftflow') }));

		apiFetch({ path: SETTINGS_ENDPOINT + '/defaults' })
			.then(setDefaults);
	}, []);

	const updateSetting = useCallback((group, key, value) => {
		setSettings(prev => ({
			...prev,
			[group]: {
				...prev[group],
				[key]: value,
			},
		}));
	}, []);

	const handleSave = useCallback(async () => {
		setSaving(true);
		setNotice(null);

		try {
			const response = await apiFetch({
				path: SETTINGS_ENDPOINT,
				method: 'POST',
				data: settings,
			});

			setSettings(response.settings);
			setNotice({ type: 'success', message: __('Settings saved.', 'giftflow') });
		} catch (err) {
			setNotice({ type: 'error', message: err.message || __('Failed to save settings.', 'giftflow') });
		} finally {
			setSaving(false);
		}
	}, [settings]);

	if (!settings || !defaults) {
		return (
			<div style={{ padding: 40, textAlign: 'center' }}>
				<Spinner />
			</div>
		);
	}

	return (
		<div className="giftflow-settings-page">
			<Flex justify="space-between" style={{ marginBottom: 16 }}>
				<Heading level={1}>{__('GiftFlow Settings', 'giftflow')}</Heading>
				<Button variant="primary" onClick={handleSave} disabled={saving}>
					{saving ? __('Saving...', 'giftflow') : __('Save Settings', 'giftflow')}
				</Button>
			</Flex>

			{notice && (
				<Notice
					status={notice.type}
					onRemove={() => setNotice(null)}
					style={{ marginBottom: 16 }}
				>
					{notice.message}
				</Notice>
			)}

			<TabPanel tabs={TABS}>
				{(tab) => (
					<Card>
						<CardHeader>
							<Heading level={2}>{tab.title}</Heading>
						</CardHeader>
						<CardBody>
							<VStack spacing={4}>
								{tab.name === 'global' && (
									<GeneralSettings
										values={settings.global}
										defaults={defaults.global}
										onChange={(key, value) => updateSetting('global', key, value)}
									/>
								)}
								{tab.name === 'display' && (
									<DisplaySettings
										values={settings.display}
										defaults={defaults.display}
										onChange={(key, value) => updateSetting('display', key, value)}
									/>
								)}
								{tab.name === 'email' && (
									<EmailSettings
										values={settings.email}
										defaults={defaults.email}
										onChange={(key, value) => updateSetting('email', key, value)}
									/>
								)}
								{tab.name === 'advanced' && (
									<AdvancedSettings
										values={settings.advanced}
										defaults={defaults.advanced}
										onChange={(key, value) => updateSetting('advanced', key, value)}
									/>
								)}
							</VStack>
						</CardBody>
					</Card>
				)}
			</TabPanel>
		</div>
	);
}

function GeneralSettings({ values, defaults, onChange }) {
	return (
		<>
			<SelectControl
				label={__('Currency', 'giftflow')}
				value={values.currency}
				onChange={(v) => onChange('currency', v)}
				options={[
					{ label: 'USD ($)', value: 'USD' },
					{ label: 'EUR (€)', value: 'EUR' },
					{ label: 'GBP (£)', value: 'GBP' },
					{ label: 'CAD (C$)', value: 'CAD' },
					{ label: 'AUD (A$)', value: 'AUD' },
					{ label: 'JPY (¥)', value: 'JPY' },
				]}
			/>

			<RangeControl
				label={__('Default Donation Amount (cents)', 'giftflow')}
				value={values.default_amount}
				onChange={(v) => onChange('default_amount', v)}
				min={100}
				max={500000}
				step={100}
			/>

			<RangeControl
				label={__('Minimum Amount (cents)', 'giftflow')}
				value={values.min_amount}
				onChange={(v) => onChange('min_amount', v)}
				min={0}
				max={100000}
				step={100}
			/>

			<RangeControl
				label={__('Maximum Amount (cents)', 'giftflow')}
				value={values.max_amount}
				onChange={(v) => onChange('max_amount', v)}
				min={1000}
				max={10000000}
				step={1000}
			/>

			<ToggleControl
				label={__('Allow Custom Donation Amounts', 'giftflow')}
				help={__('Let donors enter any amount instead of choosing from presets.', 'giftflow')}
				checked={values.allow_custom_amount}
				onChange={(v) => onChange('allow_custom_amount', v)}
			/>

			<TextControl
				label={__('Preset Amounts (comma-separated cents)', 'giftflow')}
				help={__('Example: 1000, 2500, 5000, 10000', 'giftflow')}
				value={values.preset_amounts.join(', ')}
				onChange={(v) => {
					const amounts = v.split(',').map((s) => parseInt(s.trim(), 10)).filter((n) => !isNaN(n) && n > 0);
					onChange('preset_amounts', amounts);
				}}
			/>

			<Panel>
				<PanelBody title={__('Inheritance', 'giftflow')} initialOpen={false}>
					<p style={{ color: '#666', fontSize: '0.875rem' }}>
						{__('These global settings serve as defaults. Campaigns and individual blocks can override them. Overridden values take precedence over the defaults below.', 'giftflow')}
					</p>
				</PanelBody>
			</Panel>
		</>
	);
}

function DisplaySettings({ values, defaults, onChange }) {
	return (
		<>
			<Panel>
				<PanelBody title={__('Progress Bar', 'giftflow')}>
					<ColorPicker
						color={values.progress_bar_color}
						onChange={(v) => onChange('progress_bar_color', v)}
						enableAlpha={false}
					/>
					<RangeControl
						label={__('Progress Bar Height (px)', 'giftflow')}
						value={values.progress_bar_height}
						onChange={(v) => onChange('progress_bar_height', v)}
						min={4}
						max={40}
					/>
				</PanelBody>
			</Panel>

			<ColorPicker
				color={values.primary_color}
				onChange={(v) => onChange('primary_color', v)}
				enableAlpha={false}
			/>

			<RangeControl
				label={__('Border Radius (px)', 'giftflow')}
				value={values.border_radius}
				onChange={(v) => onChange('border_radius', v)}
				min={0}
				max={24}
			/>

			<RangeControl
				label={__('Grid Columns', 'giftflow')}
				value={values.grid_columns}
				onChange={(v) => onChange('grid_columns', v)}
				min={1}
				max={4}
			/>

			<ToggleControl
				label={__('Enable Image Lightbox', 'giftflow')}
				checked={values.enable_lightbox}
				onChange={(v) => onChange('enable_lightbox', v)}
			/>

			<ToggleControl
				label={__('Full-Width Buttons by Default', 'giftflow')}
				checked={values.button_full_width}
				onChange={(v) => onChange('button_full_width', v)}
			/>
		</>
	);
}

function EmailSettings({ values, defaults, onChange }) {
	return (
		<>
			<TextControl
				label={__('"From" Name', 'giftflow')}
				value={values.from_name}
				onChange={(v) => onChange('from_name', v)}
			/>

			<TextControl
				label={__('Admin Email Address', 'giftflow')}
				type="email"
				value={values.admin_address}
				onChange={(v) => onChange('admin_address', v)}
			/>

			<TextControl
				label={__('Donor Email Subject Template', 'giftflow')}
				help={__('Use {{donor_name}}, {{amount}}, {{campaign_name}} as placeholders.', 'giftflow')}
				value={values.donor_subject_template}
				onChange={(v) => onChange('donor_subject_template', v)}
			/>

			<TextControl
				label={__('Admin Email Subject Template', 'giftflow')}
				help={__('Use {{donor_name}}, {{amount}}, {{campaign_name}} as placeholders.', 'giftflow')}
				value={values.admin_subject_template}
				onChange={(v) => onChange('admin_subject_template', v)}
			/>
		</>
	);
}

function AdvancedSettings({ values, defaults, onChange }) {
	return (
		<>
			<Panel>
				<PanelBody title={__('Google reCAPTCHA v3', 'giftflow')}>
					<ToggleControl
						label={__('Enable reCAPTCHA', 'giftflow')}
						checked={values.enable_recaptcha}
						onChange={(v) => onChange('enable_recaptcha', v)}
					/>

					{values.enable_recaptcha && (
						<>
							<TextControl
								label={__('Site Key', 'giftflow')}
								value={values.recaptcha_site_key}
								onChange={(v) => onChange('recaptcha_site_key', v)}
							/>
							<TextControl
								label={__('Secret Key', 'giftflow')}
								type="password"
								value={values.recaptcha_secret_key}
								onChange={(v) => onChange('recaptcha_secret_key', v)}
							/>
						</>
					)}
				</PanelBody>
			</Panel>

			<TextControl
				label={__('Google Maps API Key', 'giftflow')}
				type="password"
				value={values.google_maps_api_key}
				onChange={(v) => onChange('google_maps_api_key', v)}
			/>
		</>
	);
}
