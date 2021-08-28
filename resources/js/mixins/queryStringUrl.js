/**
 *
 * @param queryString
 * @returns {Promise<void>}
 */
export const setQueryInUrl = async (queryString) => {

    // Add/Remove parameters in query string
    if (history.pushState) {

        let qrStr = '';

        Object.keys(queryString).forEach((key) =>  {
            let value = queryString[key];
            let index = key;

            if (value) {
                if (qrStr == '') {
                    qrStr = `?${qrStr}${index}=${value}`
                } else {
                    qrStr = `${qrStr}&${index}=${value}`
                }
            }


        });

        var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

        if (window.location.href.includes('?')) {
            newUrl = newUrl + qrStr;
        } else {
            newUrl = newUrl + '?' + qrStr.substr(1);
        };

        window.history.pushState({path:newUrl},'',newUrl);
    }

}
