import Welcome from './Welcome';
import OverView from './OverView';

const DashboardView = () => {
  return (
    <div className="giftflow-dashboard-view">
      <Welcome />
      <OverView />
    </div>
  );
};

export default DashboardView;
