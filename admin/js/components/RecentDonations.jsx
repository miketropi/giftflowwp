const STATUS_LABELS = {
	completed: 'Completed',
	pending: 'Pending',
	failed: 'Failed',
	refunded: 'Refunded',
	cancelled: 'Cancelled',
};

export default function RecentDonations({ donations = [] }) {
	const base =
		typeof giftflow_admin !== 'undefined' && giftflow_admin.admin_url
			? giftflow_admin.admin_url
			: '';

	if (!donations || donations.length === 0) {
		return (
			<div className="giftflow-recent-donations">
				<div className="giftflow-recent-donations__header">
					<h3 className="giftflow-recent-donations__title">Recent Donations</h3>
				</div>
				<div className="giftflow-recent-donations__empty">
					<div className="giftflow-recent-donations__empty-icon">
						<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
							<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
						</svg>
					</div>
					<p className="giftflow-recent-donations__empty-text">No recent donations</p>
					<p className="giftflow-recent-donations__empty-subtext">Donations will appear here once they start coming in</p>
				</div>
			</div>
		);
	}

	return (
		<div className="giftflow-recent-donations">
			<div className="giftflow-recent-donations__header">
				<div className="giftflow-recent-donations__header-left">
					<h3 className="giftflow-recent-donations__title">Recent Donations</h3>
				</div>
				<div className="giftflow-recent-donations__header-right">
					<span className="giftflow-recent-donations__count">{donations.length} total</span>
				</div>
			</div>

			<div className="giftflow-recent-donations__list">
				{donations.map((donation) => {
					const donorEditUrl = `${base}post.php?post=${donation.donor_id}&action=edit`;
					const campaignEditUrl = `${base}post.php?post=${donation.campaign_id}&action=edit`;
					const statusKey = (donation.status || '').toLowerCase();
					const statusLabel = STATUS_LABELS[statusKey] || donation.status;

					return (
						<div key={donation.id} className="giftflow-recent-donations__row">
							<div className="giftflow-recent-donations__row-left">
								<div className="giftflow-recent-donations__avatar">
									{donation.donor_name.charAt(0).toUpperCase()}
								</div>
								<div className="giftflow-recent-donations__donor">
									<a
										href={donorEditUrl}
										className="giftflow-recent-donations__donor-name"
										target="_blank"
										rel="noopener noreferrer"
									>
										{donation.donor_name}
									</a>
									<span className="giftflow-recent-donations__donor-email">
										{donation.donor_email}
									</span>
								</div>
							</div>

							<div className="giftflow-recent-donations__row-center">
								<a
									href={campaignEditUrl}
									className="giftflow-recent-donations__campaign"
									target="_blank"
									rel="noopener noreferrer"
								>
									{donation.campaign_title}
								</a>
								<span className="giftflow-recent-donations__meta">
									{donation.date}
									<span className="giftflow-recent-donations__meta-sep">·</span>
									{donation.payment_method}
								</span>
							</div>

							<div className="giftflow-recent-donations__row-right">
								<span
									className="giftflow-recent-donations__amount"
									dangerouslySetInnerHTML={{ __html: donation.__amount }}
								/>
								<span
									className={`giftflow-recent-donations__status giftflow-recent-donations__status--${statusKey}`}
								>
									{statusLabel}
								</span>
							</div>
						</div>
					);
				})}
			</div>

			<div className="giftflow-recent-donations__footer">
				<a href={`${base}edit.php?post_type=donation`} className="giftflow-recent-donations__footer-link">
					View all donations
				</a>
			</div>
		</div>
	);
}
