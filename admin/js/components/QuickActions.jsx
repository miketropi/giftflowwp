import { useState, useEffect } from 'react';
import { FileDown } from 'lucide-react';
import Modal from './Modal';
import useCampaign from '../hooks/useCampaign';
import SelectSearch from './SelectSearch';
import { __request } from '../ulti/api';

export default function QuickActions() {
  const [isModalExportCampaignOpen, setIsModalExportCampaignOpen] = useState(false);
  const [isModalExportDonorOpen, setIsModalExportDonorOpen] = useState(false);

  const handleExportCampaign = () => {
    setIsModalExportCampaignOpen(true);
  };

  const handleExportDonor = () => {
    setIsModalExportDonorOpen(true);
  };

  return (
    <>
      <div className="giftflowwp-overview__widget">
        <h4 className="giftflowwp-overview__widget-title">Actions</h4>
        <div className="giftflowwp-overview__action-list">
          <button className="giftflowwp-overview__action-btn __monospace" type="button" onClick={handleExportCampaign}>
            <span className="giftflowwp-overview__action-icon">
              <FileDown color='#FFF' size={20} />
            </span>
            Export Campaign (.csv)
          </button>
          <button className="giftflowwp-overview__action-btn __monospace" type="button" onClick={handleExportDonor}>
            <span className="giftflowwp-overview__action-icon">
              <FileDown color='#FFF' size={20} />
            </span>
            Export Donor (.csv)
          </button>
        </div>
      </div>

      <ExportCampaignModal isModalExportCampaignOpen={isModalExportCampaignOpen} setIsModalExportCampaignOpen={setIsModalExportCampaignOpen} />
      <ExportDonorModal isModalExportDonorOpen={isModalExportDonorOpen} setIsModalExportDonorOpen={setIsModalExportDonorOpen} />
    </>
  )
}

const ExportCampaignModal = ({ isModalExportCampaignOpen, setIsModalExportCampaignOpen }) => {
  
  const { campaigns, loading, error } = useCampaign({ per_page: -1 });
  const [selectedCampaign, setSelectedCampaign] = useState(null);
  const [errorMsg, setErrorMsg] = useState(null);

  return <Modal
      size='medium'
      isOpen={isModalExportCampaignOpen}
      onClose={() => setIsModalExportCampaignOpen(false)}
      title="Export Campaign (.csv)"
      actions={[{
        text: "Export",
        variant: "primary",
        onClick: async () => {

          // setIsModalExportCampaignOpen(false);
          // console.log(selectedCampaign);

          try {
            // open api to download csv 
            const response = await __request(`/wp-json/giftflowwp/v1/campaign/csv-export?campaign_id=${selectedCampaign}`, {}, 'GET');

            const blob = new Blob([response], {
              type: 'text/csv;charset=utf-8;'
            });
            
            const url = window.URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            const titleCampaign = campaigns.find(campaign => campaign.id === selectedCampaign)?.title;
            a.download = `${titleCampaign}_${new Date().toISOString()}.csv`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            // close modal
            setIsModalExportCampaignOpen(false);
          } catch (error) {
            console.error('Error exporting campaign:', error);
            setErrorMsg(error?.responseJSON?.message || 'An error occurred while exporting the campaign. Please try again.');
          }
        }
      }, {
        text: "Close",
        variant: "secondary",
        onClick: () => {
          setIsModalExportCampaignOpen(false);
        }
      }]}
    >
    <div className="giftflowwp-export-campaign__select-container">
      <label htmlFor="export-campaign-select" className="giftflowwp-export-campaign__label">
        Select Campaign to Export
      </label>
      {loading && <div className="giftflowwp-export-campaign__loading">Loading campaigns...</div>}
      {error && <div className="giftflowwp-export-campaign__error">Error: {error.message || error.toString()}</div>}
      {!loading && !error && (
        <>
          <SelectSearch
            options={campaigns.map(campaign => ({
              value: campaign.id,
              label: `#${campaign.id} — ${campaign.title} (${campaign.status})`
            }))}
            value={selectedCampaign}
            onChange={value => {
              setSelectedCampaign(value)

              // reset errorMsg
              setErrorMsg(null);
            }}
          />
        </>
        
      )}

    {/* Notification message for user about export functionality */}
    <div className="__monospace">
      <strong>Export Info:</strong> Exporting a campaign will download a CSV file with all details for the selected campaign, including donations, donor information, dates, and amounts.
    </div>

    {errorMsg && <div className="giftflowwp-export-campaign__error">{errorMsg}</div>}
    </div>
  </Modal>
}

const ExportDonorModal = ({ isModalExportDonorOpen, setIsModalExportDonorOpen }) => {

  return <Modal
    size='small'
    isOpen={isModalExportDonorOpen}
    onClose={() => setIsModalExportDonorOpen(false)}
    title="Export Donor (.csv)"
    actions={[{
      text: "Export",
      variant: "primary",
      onClick: () => {
        setIsModalExportDonorOpen(false);
      }
    }, {
      text: "Close",
      variant: "secondary",
      onClick: () => {
        setIsModalExportDonorOpen(false);
      }
    }]}
  >
    <div className="__monospace" style={{  padding: '0 0 2rem 0', fontSize: '.8rem' }}>
      <strong>Coming Soon:</strong> Exporting donors will be available in a future update.
    </div>
  </Modal>
}