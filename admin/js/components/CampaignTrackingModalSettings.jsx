import React, { useState, useEffect } from 'react';
import Modal from './Modal';
import { getCampaigns } from '../ulti/api';
import { useDashboardStore } from '../stores/useDashboardStore';

export default function CampaignTrackingModalSettings({ isOpen, onClose, onSave }) {
  const { campaignsTracking } = useDashboardStore();
  const [campaigns, setCampaigns] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selectedCampaigns, setSelectedCampaigns] = useState(campaignsTracking);

  useEffect(() => {
    if (isOpen) {
      setLoading(true);
      setError(null);
      getCampaigns({ per_page: -1 })
        .then(data => {
          if (!data) throw new Error('Failed to fetch campaigns');
          return data;
        })
        .then(data => setCampaigns(data))
        .then(() => setLoading(false))
        .catch(err => setError(err));
    }
  }, [isOpen]);

  return <Modal
    isOpen={isOpen}
    onClose={onClose}
    title="Select Campaigns to track"
    actions={[{
      text: "Save",
      variant: "primary",
      onClick: () => {
        onSave(selectedCampaigns);
      }
    }, {
      text: "Close",
      variant: "secondary",
      onClick: () => {
        onClose();
      }
    }]}
  >
    <>
      {
        loading && <div>Loading...</div>
      }
      {
        error && <div>Error: {error}</div>
      }
      {
        loading === false && campaigns.length > 0 && 
        <div className="giftflowwp-campaigns-list__settings-container">
          <div className="giftflowwp-campaigns-list__auto-message">
            No campaigns selected. The system will automatically display the latest campaigns.
          </div>
          <br />
          <div className="giftflowwp-campaigns-list__select-wrapper">
            <select
              id="campaign-multiselect"
              multiple
              className="giftflowwp-campaigns-list__multiselect"
              onChange={e => {
                setSelectedCampaigns(Array.from(e.target.selectedOptions).map(opt => opt.value));
              }}
            >
              {campaigns.map(campaign => {
                let statusEmoji, statusLabel;
                switch (campaign.status) {
                  case 'active':
                    statusEmoji = '✅';
                    statusLabel = 'active';
                    break;
                  case 'closed':
                    statusEmoji = '❌';
                    statusLabel = 'closed';
                    break;
                  case 'completed': 
                    statusEmoji = '🏁';
                    statusLabel = 'completed';
                    break;
                  case 'pending':
                  default:
                    statusEmoji = '⏳';
                    statusLabel = 'pending';
                    break;
                }
                return <option
                  key={campaign.id}
                  value={campaign.id}
                  selected={selectedCampaigns.includes(campaign.id)}
                  className="giftflowwp-campaigns-list__option"
                  dangerouslySetInnerHTML={{ __html: `#ID ${ campaign.id } — ${ campaign.title } — ${ statusEmoji } ${ statusLabel } (${ campaign.percentage }%)` }}
                >
                </option>
              })}
            </select>
          </div>
          <div className="giftflowwp-campaigns-list__help-text __monospace">
            Hold <span className="giftflowwp-campaigns-list__key">Ctrl</span> (or <span className="giftflowwp-campaigns-list__key">Cmd</span> on Mac) and click to select multiple campaigns.
          </div>
          
        </div>
      }
    </>
  </Modal>
}