import { render, createElement } from '@wordpress/element';
import DashboardView from '../components/DashboardView';
import { createRoot } from '@wordpress/element';


((w) => {
  'use strict';

  const DashboardView_Init = () => {
    const elem = document.querySelector('#GFWP_DASHBOARD_VIEW_ROOT');
    if (elem) {
      const root = createRoot(elem);
      root.render(<DashboardView />);
    }
  }

  // dom ready 
  document.addEventListener('DOMContentLoaded', () => {
    DashboardView_Init();
  });
})(window)