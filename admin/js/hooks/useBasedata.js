import { useState, useEffect } from 'react';
import { getBasedata } from '../ulti/api';

export default function useBasedata() {
  const [basedata, setBasedata] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    let isMounted = true;
    setLoading(true);
    setError(null);

    getBasedata({})
      .then(data => {
        if (!data) throw new Error('Failed to fetch basedata');
        return data;
      })
      .then(data => {
        if (isMounted) {
          setBasedata(data);
          setLoading(false);
        }
      })
      .catch(err => {
        if (isMounted) {
          setError(err.message || 'Unknown error');
          setLoading(false);
        }
      });

    return () => {
      isMounted = false;
    };
  }, []);

  return { basedata, loading, error };
}