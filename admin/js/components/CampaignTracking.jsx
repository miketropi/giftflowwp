import React, { useState, useEffect } from 'react';
import useCampaign from "../hooks/useCampaign";
import { Wrench } from 'lucide-react';
import CampaignTrackingModalSettings from './CampaignTrackingModalSettings';
import { useDashboardStore } from '../stores/useDashboardStore';

const CampaignTracking = () => {
  const { campaignsTracking, setCampaignsTracking } = useDashboardStore();
  const { campaigns, loading, error, updateParams } = useCampaign({ per_page: 3, includeIds: campaignsTracking });
  const [isModalOpen, setIsModalOpen] = useState(false);

  if (loading) return (
    <div className="giftflow-overview__widget">
      <h4 className="giftflow-overview__widget-title">Campaigns Tracking</h4>
      <div className="giftflow-campaigns-list__loading">Loading...</div>
    </div>
  );
  
  if (error) return (
    <div className="giftflow-overview__widget">
      <h4 className="giftflow-overview__widget-title">Campaigns Tracking</h4>
      <div className="giftflow-campaigns-list__error">Error: {error}</div>
    </div>
  );

  const onSave = (campaigns) => {
    // updateParams
    // console.log(updateParams);
    setCampaignsTracking(campaigns);
    updateParams({ include: campaigns });
    // console.log("Save", campaigns);
    setIsModalOpen(false);
  };

  return (
    <>
      <div className="giftflow-overview__widget">
        <div className="giftflow-overview__widget-header">
          <h4 className="giftflow-overview__widget-title">Campaigns Tracking</h4>
          <button className="giftflow-overview__widget-header-btn" onClick={() => setIsModalOpen(true)}>
            <Wrench size={16} />
          </button>
        </div>
        <div className="giftflow-campaigns-list">
          <ul className="giftflow-campaigns-list__ul">
            {campaigns.map(campaign => (
              <li key={campaign.id} className="giftflow-campaigns-list__item">
                <div className="giftflow-campaigns-list__content">
                  <a href={campaign.link} target="_blank" rel="noopener noreferrer" className="giftflow-campaigns-list__title-link">
                    <h5 className="giftflow-campaigns-list__title" dangerouslySetInnerHTML={{ __html: campaign.title }}></h5>
                  </a>
                  
                  {/* <div className="giftflow-campaigns-list__excerpt">
                    <span dangerouslySetInnerHTML={{ __html: campaign.excerpt }} />
                  </div> */}
                  
                  <div className="giftflow-campaigns-list__stats">
                    <span className="giftflow-campaigns-list__stat">
                      <strong>Goal:</strong>{" "}
                      <span className="__monospace" dangerouslySetInnerHTML={{ __html: campaign.__goal_amount }} />
                    </span>
                    <span className="giftflow-campaigns-list__stat">
                      <strong>Raised:</strong>{" "}
                      <span className="__monospace" dangerouslySetInnerHTML={{ __html: campaign.__raised_amount }} />
                    </span>
                    <span className="giftflow-campaigns-list__stat">
                      <strong>Status:</strong>{" "}
                      <span className={`giftflow-campaigns-list__status giftflow-campaigns-list__status--${campaign.status}`}>
                        {campaign.status.charAt(0).toUpperCase() + campaign.status.slice(1)}
                      </span>
                    </span>
                  </div>
                  
                  <div className="giftflow-campaigns-list__progress-bar">
                    <div 
                      className="giftflow-campaigns-list__progress-fill"
                      style={{ width: `${campaign.percentage}%` }}
                    />
                  </div>
                  
                  <div className="giftflow-campaigns-list__meta">
                    <span className="giftflow-campaigns-list__percentage">
                      {campaign.percentage}% funded
                    </span>
                    {campaign.start_date && (
                      <span className="giftflow-campaigns-list__date">
                        <strong>Start:</strong> <span className="__monospace">{campaign.start_date.split("T")[0]}</span>
                      </span>
                    )}
                    {campaign.end_date && (
                      <span className="giftflow-campaigns-list__date">
                        <strong>End:</strong> <span className="__monospace">{campaign.end_date.split("T")[0]}</span>
                      </span>
                    )}
                  </div>
                </div>
              </li>
            ))}
          </ul>
        </div>
      </div>
      <CampaignTrackingModalSettings 
        isOpen={isModalOpen} 
        onClose={() => setIsModalOpen(false)}
        onSave={onSave}
      />
    </>
  );
};

export default CampaignTracking;