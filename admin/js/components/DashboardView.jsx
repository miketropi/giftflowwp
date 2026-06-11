import { useState, useCallback } from 'react';
import Welcome from './Welcome';
import OverView from './OverView';
import HelpTab from './HelpTab';
import { LayoutGrid, HelpCircle } from 'lucide-react';

const TABS = [
  { id: 'overview', label: 'Overview', icon: LayoutGrid },
  { id: 'help', label: 'Help', icon: HelpCircle },
];

const getInitialTab = () => {
  const params = new URLSearchParams(window.location.search);
  const tab = params.get('tab');
  return TABS.some(t => t.id === tab) ? tab : 'overview';
};

const updateUrlTab = (tabId) => {
  const url = new URL(window.location.href);
  if (tabId === 'overview') {
    url.searchParams.delete('tab');
  } else {
    url.searchParams.set('tab', tabId);
  }
  window.history.replaceState(null, '', url.toString());
};

const DashboardView = () => {
  const [activeTab, setActiveTab] = useState(getInitialTab);

  const handleTabChange = useCallback((tabId) => {
    setActiveTab(tabId);
    updateUrlTab(tabId);
  }, []);

  return (
    <div className="giftflow-dashboard-view__shell">
      <Welcome />

      <nav className="giftflow-dashboard-view__tabs" role="tablist">
        {TABS.map(tab => (
          <button
            key={tab.id}
            role="tab"
            aria-selected={activeTab === tab.id}
            className={`giftflow-dashboard-view__tab${activeTab === tab.id ? ' giftflow-dashboard-view__tab--active' : ''}`}
            onClick={() => handleTabChange(tab.id)}
          >
            <tab.icon size={16} strokeWidth={2} aria-hidden="true" />
            {tab.label}
          </button>
        ))}
      </nav>

      <div role="tabpanel">
        {activeTab === 'overview' && <OverView />}
        {activeTab === 'help' && <HelpTab />}
      </div>
    </div>
  );
};

export default DashboardView;
