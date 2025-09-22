import React, { useState, } from 'react';
import useCampaign from "../hooks/useCampaign";
import { Wrench } from 'lucide-react';
import CampaignTrackingModalSettings from './CampaignTrackingModalSettings';

const CampaignTracking = () => {
  const { campaigns, loading, error } = useCampaign({ per_page: 3 });
  const [isModalOpen, setIsModalOpen] = useState(false);

  if (loading) return (
    <div className="giftflowwp-overview__widget">
      <h4 className="giftflowwp-overview__widget-title">Campaigns Tracking</h4>
      <div className="giftflowwp-campaigns-list__loading">Loading...</div>
    </div>
  );
  
  if (error) return (
    <div className="giftflowwp-overview__widget">
      <h4 className="giftflowwp-overview__widget-title">Campaigns Tracking</h4>
      <div className="giftflowwp-campaigns-list__error">Error: {error}</div>
    </div>
  );

  const onSave = (campaigns) => {
    console.log("Save", campaigns);
  };

  return (
    <>
      <div className="giftflowwp-overview__widget">
        <div className="giftflowwp-overview__widget-header">
          <h4 className="giftflowwp-overview__widget-title">Campaigns Tracking</h4>
          <button className="giftflowwp-overview__widget-header-btn" onClick={() => setIsModalOpen(true)}>
            <Wrench size={16} />
          </button>
        </div>
        <div className="giftflowwp-campaigns-list">
          <ul className="giftflowwp-campaigns-list__ul">
            {campaigns.map(campaign => (
              <li key={campaign.id} className="giftflowwp-campaigns-list__item">
                <div className="giftflowwp-campaigns-list__content">
                  <a href={campaign.link} target="_blank" rel="noopener noreferrer" className="giftflowwp-campaigns-list__title-link">
                    <h5 className="giftflowwp-campaigns-list__title" dangerouslySetInnerHTML={{ __html: campaign.title }}></h5>
                  </a>
                  
                  {/* <div className="giftflowwp-campaigns-list__excerpt">
                    <span dangerouslySetInnerHTML={{ __html: campaign.excerpt }} />
                  </div> */}
                  
                  <div className="giftflowwp-campaigns-list__stats">
                    <span className="giftflowwp-campaigns-list__stat">
                      <strong>Goal:</strong>{" "}
                      <span className="__monospace" dangerouslySetInnerHTML={{ __html: campaign.__goal_amount }} />
                    </span>
                    <span className="giftflowwp-campaigns-list__stat">
                      <strong>Raised:</strong>{" "}
                      <span className="__monospace" dangerouslySetInnerHTML={{ __html: campaign.__raised_amount }} />
                    </span>
                    <span className="giftflowwp-campaigns-list__stat">
                      <strong>Status:</strong>{" "}
                      <span className={`giftflowwp-campaigns-list__status giftflowwp-campaigns-list__status--${campaign.status}`}>
                        {campaign.status.charAt(0).toUpperCase() + campaign.status.slice(1)}
                      </span>
                    </span>
                  </div>
                  
                  <div className="giftflowwp-campaigns-list__progress-bar">
                    <div 
                      className="giftflowwp-campaigns-list__progress-fill"
                      style={{ width: `${campaign.percentage}%` }}
                    />
                  </div>
                  
                  <div className="giftflowwp-campaigns-list__meta">
                    <span className="giftflowwp-campaigns-list__percentage">
                      {campaign.percentage}% funded
                    </span>
                    {campaign.start_date && (
                      <span className="giftflowwp-campaigns-list__date">
                        <strong>Start:</strong> <span className="__monospace">{campaign.start_date.split("T")[0]}</span>
                      </span>
                    )}
                    {campaign.end_date && (
                      <span className="giftflowwp-campaigns-list__date">
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