import { create } from 'zustand';
import { immer } from 'zustand/middleware/immer';
import { persist } from 'zustand/middleware'

export const useDashboardStore = create(
  persist(
    immer((set) => ({
      // State
      loading: false,
      campaignsTracking: [],

      // Actions
      setLoading: (loading) => set({ loading }),
      setCampaignsTracking: (campaignsTracking) => set({ campaignsTracking }),
      
    })),
  // Add persist config to cache campaignsTracking in localStorage
  {
    name: 'giftflow_dashboard_store',
    partialize: (state) => ({
      campaignsTracking: state.campaignsTracking
    }),
    // Optionally, you can specify storage if you want to use sessionStorage or another storage
    // storage: createJSONStorage(() => sessionStorage),
  }
  )
);
