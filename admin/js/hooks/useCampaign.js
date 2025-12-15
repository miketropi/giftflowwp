import { useState, useEffect } from 'react';
import { getCampaigns } from '../ulti/api';

export default function useCampaign(args = {}) {
  
  // Set default values for params using destructuring
  const {
    includeIds = [],
    excludeIds = [],
    search = '',
    order = 'desc',
    orderby = 'date',
    page = 1,
    per_page = 10,
  } = args || {};

  const [campaigns, setCampaigns] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [params, setParams] = useState({
    include: includeIds || [],
    exclude: excludeIds || [],
    search: search || '',
    order: order || 'desc',
    orderby: orderby || 'date',
    page: page || 1,
    per_page: per_page || 10,
  });

  // Function to update params state
  const updateParams = (newParams) => {
    setParams(prev => ({
      ...prev,
      ...newParams
    }));
  };

  useEffect(() => {
    let isMounted = true;
    setLoading(true);
    setError(null);

    // Build the API URL with query parameters
    const queryString = Object.entries(params)
      .filter(([_, value]) => value !== undefined && value !== null && value !== '')
      .map(([key, value]) => {
        if (Array.isArray(value)) {
          return value.map(v => `${encodeURIComponent(key)}[]=${encodeURIComponent(v)}`).join('&');
        }
        return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
      })
      .join('&');
    // const urlWithParams = `/wp-json/giftflowwp/v1/campaigns${queryString ? `?${queryString}` : ''}`;

    // Replace this URL with your actual API endpoint
    getCampaigns(params)
      .then((data) => {
        if (!data) throw new Error('Failed to fetch campaigns');
        return data;
      })
      .then((data) => {
        if (isMounted) {
          setCampaigns(data);
          setLoading(false);
        }
      })
      .catch((err) => {
        if (isMounted) {
          setError(err.message || 'Unknown error');
          setLoading(false);
        }
      });

    return () => {
      isMounted = false;
    };
  }, [params]);

  return { campaigns, loading, error, updateParams };
}
