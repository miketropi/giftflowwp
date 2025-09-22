import React from 'react';
import { Sparkles } from 'lucide-react';

const Welcome = () => {
  return (
    <div className="giftflowwp-welcome">
      <div className="giftflowwp-welcome__header">
        <div className="giftflowwp-welcome__logo-section">
          <Sparkles width={48} height={48} />
          <div className="giftflowwp-welcome__title-section">
            <h2 className="giftflowwp-welcome__title">GiftFlowWP Dashboard</h2>
            <p className="giftflowwp-welcome__subtitle __monospace">Your hub for managing fundraising campaigns and settings.</p>
          </div>
        </div>
      </div>

      <div className="giftflowwp-welcome__content">
        <div className="giftflowwp-welcome__left-column">
          <div className="giftflowwp-welcome__features">
            <h3>Key Features</h3>
            <ul className="giftflowwp-welcome__features-list __monospace">
              <li>🚀 <strong>Create and launch new fundraising campaigns</strong> in just a few clicks.</li>
              <li>⚙️ <strong>Customize plugin settings</strong> to match your organization's needs.</li>
              <li>📊 <strong>Track campaign progress</strong> and donor engagement from your dashboard.</li>
            </ul>
          </div>
        </div>

        <div className="giftflowwp-welcome__right-column">
          <div className="giftflowwp-welcome__actions">
            <a
              href="admin.php?page=giftflowwp_create_campaign"
              className="giftflowwp-welcome__action-btn"
            >
              <span role="img" aria-label="Create">➕</span> Create Campaign
            </a>
            <a
              href="admin.php?page=giftflowwp_settings"
              className="giftflowwp-welcome__action-btn"
            >
              <span role="img" aria-label="Settings">⚙️</span> Go to Settings
            </a>
          </div>

          <div className="giftflowwp-welcome__help">
            <h4>Need Help?</h4>
            <p>Visit our <a href="https://giftflowwp.com/docs" target="_blank" rel="noopener noreferrer">documentation</a> or <a href="https://giftflowwp.com/support" target="_blank" rel="noopener noreferrer">contact support</a>.</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Welcome;
