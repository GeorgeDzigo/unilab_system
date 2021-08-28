import axios from 'axios'

/**
 * Sent Request To The Server
 *
 * @param request
 * @returns {Promise<AxiosResponse<any>>}
 */
export const getData = (request) => {
    // Set Config
    let config = {
        method: request.method,
        url: request.url
    }
    // Check Request
    request.method == 'GET' || request.method == 'DELETE'
        ? config.params = request.data
        : config.data = request.data
    // Send Request With Axios
    return axios(config).then(response => {
        // Return Response
        return response.status ? response.data : null
    }).catch(error => {

        // Return Error Response
        return {
            data: error.response ? error.response.data : {},
            code: error.response ? error.response.status : 500,
            message: error.response ? error.response.data.message : ''
        }
    })
}
