/**
 * API utilities

 * @package GiftFlow
 * @since v1.0.0
 */

/**
 * Make a request to the API.

 * @param {string} url The URL to request.
 * @param {object} data The data to send.
 * @param {string} method The method to use.
 * @returns {Promise<object>} The response from the API.
 */
export const __request = async (url, data = {}, method = 'GET') => {
  // set nonce.
  data.nonce = data.nonce || giftflow_admin.nonce;

  // Make the request.
  const rest = await jQuery.ajax({
    method,
    url,
    data,
    headers: {
      'Content-Type': 'application/json',
      "X-WP-Nonce": giftflow_admin.rest_nonce
    },
    error: (error) => {
      console.error('Error:', error);
    }
  })

  if (rest && rest.error) {
    // If the response contains an error property, throw it as an exception.
    throw new Error(rest.error || 'Request error');
  }

  return rest;
}

/**
 * Get campaigns.
 * @param {object} query The query parameters.
 * @returns {Promise<object>} The response from the API.
 */
export const getCampaigns = async (query = {}) => {
  // Build the API URL with query parameters.
  const queryString = Object.entries(query)
    .filter(([_, value]) => value !== undefined && value !== null && value !== '')
    .map(([key, value]) => {
      if (Array.isArray(value)) {
        return value.map(v => `${encodeURIComponent(key)}[]=${encodeURIComponent(v)}`).join('&');
      }
      return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
    })
    .join('&');

  const urlWithParams = `/wp-json/giftflow/v1/campaigns${queryString ? `?${queryString}` : ''}`;

  return __request(urlWithParams, {});
}

/**
 * Get basedata.
 * @returns {Promise<object>} The response from the API.
 */
export const getBasedata = async () => {
  const urlWithParams = `/wp-json/giftflow/v1/dashboard/overview`;

  return __request(urlWithParams, {});
}

/**
 * Get dashboard statistics charts.
 * @param {object} query The query parameters.
 * @returns {Promise<object>} The response from the API.
 */
export const getDashboardStatisticsCharts = async (query = {}) => {
  const queryString = Object.entries(query)
    .filter(([_, value]) => value !== undefined && value !== null && value !== '')
    .map(([key, value]) => {
      if (Array.isArray(value)) {
        return value.map(v => `${encodeURIComponent(key)}[]=${encodeURIComponent(v)}`).join('&');
      }
      return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
    })

  const urlWithParams = `/wp-json/giftflow/v1/dashboard/statistics/charts${queryString ? `?${queryString}` : ''}`;

  return __request(urlWithParams, {});
}