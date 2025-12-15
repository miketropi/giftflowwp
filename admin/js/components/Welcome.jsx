import React from 'react';
import { Waves, MousePointerClick, Settings, TrendingUp } from 'lucide-react';

const Welcome = () => {

  const create_campaign_url = giftflowwp_admin.admin_url + 'post-new.php?post_type=campaign';
  const settings_url = giftflowwp_admin.admin_url + 'admin.php?page=giftflowwp-settings';

  const features = [
    {
      icon: MousePointerClick,
      title: 'Create and launch new fundraising campaigns',
      description: 'in just a few clicks.'
    },
    
    {
      icon: Settings,
      title: 'Customize plugin settings',
      description: 'to match your organization\'s needs.'
    },
    {
      icon: TrendingUp,
      title: 'Track campaign progress',
      description: 'and donor engagement from your dashboard.'
    }
  ];

  return (
    <div className="giftflowwp-welcome">
      <div className="giftflowwp-welcome__header">
        <div className="giftflowwp-welcome__logo-section">
          <Waves width={48} height={48} color='black' />
          <div className="giftflowwp-welcome__title-section">
            <h2 className="giftflowwp-welcome__title">GiftFlowWP Dashboard</h2>
            <p className="giftflowwp-welcome__subtitle">Your hub for managing fundraising campaigns and settings.</p>
          </div>
        </div>
      </div>

      <div className="giftflowwp-welcome__content">
        <div className="giftflowwp-welcome__left-column">
          <div className="giftflowwp-welcome__features">
            <h3>Key Features</h3>
            <div className="giftflowwp-welcome__features-list ">
              {
                features.map((feature, index) => (
                  <div className='giftflowwp-welcome__features-item' key={index}>
                    <feature.icon width={16} height={16} color='black' />
                    <div><strong>{feature.title}</strong> {feature.description}</div>
                  </div>
                ))
              }
            </div>
          </div>
        </div>

        <div className="giftflowwp-welcome__right-column">
          <div className="giftflowwp-welcome__actions">
            <a
              href={ create_campaign_url }
              className="giftflowwp-welcome__action-btn"
            >
              <span role="img" aria-label="Create">➕</span> Create Campaign
            </a>
            <a
              href={ settings_url }
              className="giftflowwp-welcome__action-btn"
            >
              <span role="img" aria-label="Settings">⚙️</span> Go to Settings
            </a>
          </div>

          <div className="giftflowwp-welcome__help">
            <h4>Need Help?</h4>
            <p>Visit our <a href="#" target="_blank" rel="noopener noreferrer">documentation</a> or <a href="#" target="_blank" rel="noopener noreferrer">contact support</a>.</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Welcome;
