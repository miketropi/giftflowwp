import React from 'react';
import QuickActions from './QuickActions';
import CampaignTracking from './CampaignTracking';
import useBasedata from '../hooks/useBasedata';
import Card from './Card';
import RecentDonations from './RecentDonations';
import DonationsOverviewChart from './DonationsOverviewChart';
import { DollarSign, ClipboardCheck, Users } from 'lucide-react';

const OverView = () => {
  const { basedata, error, loading } = useBasedata();

  const cards = [
    {
      icon: <DollarSign size={ 24 } />,
      value: basedata?.__total_raised,
      title: 'Total Raised'
    },
    {
      icon: <ClipboardCheck size={ 24 } />,
      value: basedata?.total_active_campaigns,
      title: 'Active Campaigns'
    },
    {
      icon: <Users size={ 24 } />,
      value: basedata?.total_donors,
      title: 'Total Donors'
    },
  ];

  return (
    <div className="giftflowwp-overview">
      <div className="giftflowwp-overview__main">
        <h3 className="giftflowwp-overview__title">Dashboard Overview</h3>

        <div className="giftflowwp-overview__chart">
          <DonationsOverviewChart />
        </div>
        
        <div className="giftflowwp-overview__stats">
          { cards.map((card, index) => (
            <Card key={index} icon={card.icon} value={card.value} title={card.title} isLoading={loading} />
          )) }
        </div>

        <div className="giftflowwp-overview__recent-activity">
          <RecentDonations donations={basedata?.recent_donations} />
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