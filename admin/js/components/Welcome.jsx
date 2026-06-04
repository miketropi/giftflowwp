import React from 'react';
import { MousePointerClick, Settings, TrendingUp } from 'lucide-react';

const Welcome = () => {
  const admin = typeof giftflow_admin !== 'undefined' ? giftflow_admin : {};
  const base = admin.admin_url || '';
  const create_campaign_url = `${base}post-new.php?post_type=campaign`;
  const settings_url = `${base}admin.php?page=giftflow-settings`;
  const docs_url = admin.docs_url || 'https://giftflow-doc.beplus-agency.cloud/';
  const support_url = admin.support_url || 'https://giftflow.beplus-agency.cloud/contact';

  let doc_host = 'giftflow-doc.beplus-agency.cloud';
  try {
    doc_host = new URL(docs_url).hostname;
  } catch {
    // keep default
  }

  const features = [
    {
      icon: MousePointerClick,
      title: 'Create campaigns',
      description: 'Launch new fundraising campaigns in just a few clicks.',
    },
    {
      icon: Settings,
      title: 'Plugin settings',
      description: 'Customize options to match your organization\'s needs.',
    },
    {
      icon: TrendingUp,
      title: 'Track progress',
      description: 'Monitor campaign performance and donor engagement.',
    },
  ];

  return (
    <>
      <header className="giftflow-dashboard-view__masthead" aria-labelledby="giftflow-dashboard-heading">
        <div className="giftflow-dashboard-view__masthead-main">
          <h2 id="giftflow-dashboard-heading" className="giftflow-dashboard-view__masthead-title">
            Dashboard
          </h2>
          <p className="giftflow-dashboard-view__masthead-lead">
            Your hub for managing fundraising campaigns, donations, and plugin settings.
          </p>
          <div className="giftflow-dashboard-view__masthead-actions">
            <a className="button button-primary" href={create_campaign_url}>
              <span className="dashicons dashicons-plus-alt" aria-hidden="true" />
              Create campaign
            </a>
            <a className="button" href={settings_url}>
              <span className="dashicons dashicons-admin-settings" aria-hidden="true" />
              Settings
            </a>
          </div>
          <p className="giftflow-dashboard-view__masthead-meta">
            <a href={docs_url} target="_blank" rel="noopener noreferrer">
              {doc_host}
            </a>
            {' · '}
            <a href={support_url} target="_blank" rel="noopener noreferrer">
              Contact support
            </a>
          </p>
        </div>
      </header>

      <div className="giftflow-dashboard-view__features" aria-label="Key features">
        {features.map((feature, index) => (
          <div className="giftflow-dashboard-view__feature-card" key={index}>
            <span className="giftflow-dashboard-view__feature-card-icon" aria-hidden="true">
              <feature.icon size={20} strokeWidth={1.75} />
            </span>
            <div className="giftflow-dashboard-view__feature-card-body">
              <strong>{feature.title}</strong>
              <p>{feature.description}</p>
            </div>
          </div>
        ))}
      </div>
    </>
  );
};

export default Welcome;
