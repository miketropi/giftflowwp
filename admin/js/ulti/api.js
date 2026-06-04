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
  data.nonce = data.nonce || giftflow_admin.nonce;

  let jqXHR;
  try {
    jqXHR = await jQuery.ajax({
      method,
      url,
      data,
      dataType: 'json',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': giftflow_admin.rest_nonce,
      },
    });
  } catch (error) {
    const jq = error && error.responseJSON !== undefined ? error : null;
    const status = jq ? jq.status : 'unknown';
    const statusText = jq ? jq.statusText : '';
    const responseText = jq ? jq.responseText : '';

    console.error(
      'GiftFlow API request failed:',
      { url, method, status, statusText, responseText }
    );

    if (jq && jq.responseJSON && (jq.responseJSON.error || jq.responseJSON.code)) {
      throw new Error(
        jq.responseJSON.error || jq.responseJSON.message || 'Request error'
      );
    }
    throw error;
  }

  if (jqXHR && (jqXHR.error || jqXHR.code)) {
    throw new Error(jqXHR.error || jqXHR.message || 'Request error');
  }

  return jqXHR;
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