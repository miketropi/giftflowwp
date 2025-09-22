import { FileDown } from 'lucide-react';

export default function QuickActions() {
  return (
    <div className="giftflowwp-overview__widget">
      <h4 className="giftflowwp-overview__widget-title">Quick Actions</h4>
      <div className="giftflowwp-overview__action-list">
        <button className="giftflowwp-overview__action-btn __monospace" type="button">
          <span className="giftflowwp-overview__action-icon">
            <FileDown color='#FFF' size={20} />
          </span>
          Export Campaign
        </button>
        <button className="giftflowwp-overview__action-btn __monospace" type="button">
          <span className="giftflowwp-overview__action-icon">
            <FileDown color='#FFF' size={20} />
          </span>
          Export Donor
        </button>
      </div>
    </div>
  )
}