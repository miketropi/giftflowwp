/**
 * Settings Panel Component with Inheritance Indicators.
 *
 * Renders a settings field with visual indicators showing whether
 * the value is inherited from globals, overridden at campaign level,
 * or set at block instance level.
 */
import { __, sprintf } from '@wordpress/i18n';
import {
	BaseControl,
	Flex,
	FlexItem,
	Icon,
	Tooltip,
} from '@wordpress/components';

function InheritanceBadge({ level, inherited }) {
	if (!inherited) {
		return null;
	}

	const labels = {
		global: __('Inherited from global defaults', 'giftflow'),
		campaign: __('Overridden by campaign settings', 'giftflow'),
		block: __('Set at block level', 'giftflow'),
	};

	const icons = {
		global: 'admin-site',
		campaign: 'megaphone',
		block: 'block-default',
	};

	return (
		<Tooltip text={labels[level] || ''}>
			<span
				style={{
					display: 'inline-flex',
					alignItems: 'center',
					gap: 4,
					fontSize: 11,
					color: '#757575',
					cursor: 'help',
				}}
			>
				<Icon icon={icons[level] || 'admin-site'} size={14} />
				{labels[level]}
			</span>
		</Tooltip>
	);
}

export default function SettingsPanel({
	label,
	help,
	children,
	inheritedFrom = null,
	overriddenBy = null,
}) {
	const showInheritance = inheritedFrom || overriddenBy;

	return (
		<BaseControl
			id={`giftflow-setting-${label.replace(/\s+/g, '-').toLowerCase()}`}
			label={
				<Flex justify="space-between" style={{ width: '100%' }}>
					<FlexItem>{label}</FlexItem>
					{showInheritance && (
						<FlexItem>
							{inheritedFrom && (
								<InheritanceBadge level={inheritedFrom} inherited={true} />
							)}
							{overriddenBy && (
								<InheritanceBadge level={overriddenBy} inherited={true} />
							)}
						</FlexItem>
					)}
				</Flex>
			}
			help={help}
		>
			{children}
		</BaseControl>
	);
}
