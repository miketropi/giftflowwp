/**
 * API 
 * 
 * @package GiftFlowWp
 * @since v1.0.0
 */

/**
 * 
 * @param {*} url 
 * @param {*} data 
 * @param {*} method 
 * @returns 
 */
export const __request = async (url, data = {}, method = 'GET') => {

  // set nonce
  data.nonce = data.nonce || giftflowwp_admin.nonce;

  const rest = await jQuery.ajax({
    method,
    url,
    data,
    headers: {
      'Content-Type': 'application/json',
      "X-WP-Nonce": giftflowwp_admin.rest_nonce
    },
    error: (error) => {
      console.error('Error:', error);
    }
  })

  if (rest && rest.error) {
    // If the response contains an error property, throw it as an exception
    throw new Error(rest.error || 'Request error');
  }

  return rest;
}

export const getCampaigns = async (query = {}) => {
  // Build the API URL with query parameters
  const queryString = Object.entries(query)
    .filter(([_, value]) => value !== undefined && value !== null && value !== '')
    .map(([key, value]) => {
      if (Array.isArray(value)) {
        return value.map(v => `${encodeURIComponent(key)}[]=${encodeURIComponent(v)}`).join('&');
      }
      return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
    })
    .join('&');

  const urlWithParams = `/wp-json/giftflowwp/v1/campaigns${queryString ? `?${queryString}` : ''}`;

  return __request(urlWithParams, {});
}

export const getBasedata = async () => {
  const urlWithParams = `/wp-json/giftflowwp/v1/dashboard/overview`;

  return __request(urlWithParams, {});
}