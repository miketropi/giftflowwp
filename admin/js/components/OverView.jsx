import React from 'react';
import QuickActions from './QuickActions';
import CampaignTracking from './CampaignTracking';

const OverView = () => {
  return (
    <div className="giftflowwp-overview">
      <div className="giftflowwp-overview__main">
        <h3 className="giftflowwp-overview__title">Dashboard Overview</h3>
        
        <div className="giftflowwp-overview__stats">
          <div className="giftflowwp-overview__stat-card">
            <div className="giftflowwp-overview__stat-icon">💰</div>
            <div className="giftflowwp-overview__stat-content">
              <span className="giftflowwp-overview__stat-value">$12,450</span>
              <span className="giftflowwp-overview__stat-label">Total Raised</span>
            </div>
          </div>
          
          <div className="giftflowwp-overview__stat-card">
            <div className="giftflowwp-overview__stat-icon">🎯</div>
            <div className="giftflowwp-overview__stat-content">
              <span className="giftflowwp-overview__stat-value">8</span>
              <span className="giftflowwp-overview__stat-label">Active Campaigns</span>
            </div>
          </div>
          
          <div className="giftflowwp-overview__stat-card">
            <div className="giftflowwp-overview__stat-icon">👥</div>
            <div className="giftflowwp-overview__stat-content">
              <span className="giftflowwp-overview__stat-value">156</span>
              <span className="giftflowwp-overview__stat-label">Total Donors</span>
            </div>
          </div>
        </div>

        <div className="giftflowwp-overview__recent-activity">
          <h4>Recent Activity</h4>
          <div className="giftflowwp-overview__activity-list">
            <div className="giftflowwp-overview__activity-item">
              <div className="giftflowwp-overview__activity-icon">💝</div>
              <div className="giftflowwp-overview__activity-content">
                <span className="giftflowwp-overview__activity-text">New donation of $250 received</span>
                <span className="giftflowwp-overview__activity-time">2 hours ago</span>
              </div>
            </div>
            
            <div className="giftflowwp-overview__activity-item">
              <div className="giftflowwp-overview__activity-icon">🎉</div>
              <div className="giftflowwp-overview__activity-content">
                <span className="giftflowwp-overview__activity-text">"Emergency Fund" reached 75% of goal</span>
                <span className="giftflowwp-overview__activity-time">5 hours ago</span>
              </div>
            </div>
            
            <div className="giftflowwp-overview__activity-item">
              <div className="giftflowwp-overview__activity-icon">👤</div>
              <div className="giftflowwp-overview__activity-content">
                <span className="giftflowwp-overview__activity-text">New donor registered</span>
                <span className="giftflowwp-overview__activity-time">1 day ago</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="giftflowwp-overview__sidebar">
        <QuickActions />
        <CampaignTracking />
      </div>
    </div>
  );
};

export default OverView;