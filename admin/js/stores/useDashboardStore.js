import { create } from 'zustand';
import { immer } from 'zustand/middleware/immer';

export const useDashboardStore = create(
  immer((set) => ({
    // State
    loading: false,

    // Actions
    setLoading: (loading) => set({ loading }),
  }))
);
