import axios from 'axios';
import {BASE_URL} from '@/config/constants';
// import * as Sentry from "@sentry/vue";

/**
 * Main function
 */
 const callApi = axios.create({
    baseURL: BASE_URL
});

/*
 |--------------------------------------------------------------------------
 | Configs
 |--------------------------------------------------------------------------
 | Cấu hình trước request
 | See: https://www.npmjs.com/package/axios#config-defaults
 */
callApi.interceptors.request.use(async config => {
    // Cấu hình thời gian timeout
    config.timeout = 30000; //30s

    // Cấu hình header
    config.headers = {
        ...config.headers,
        'content-type': 'application/json',
    }

    return config;

}, function(error) {
    return Promise.reject(error);
});

/*
 |--------------------------------------------------------------------------
 | Configs
 |--------------------------------------------------------------------------
 | Cấu hình sau khi request thành công
 | See: https://www.npmjs.com/package/axios#interceptors
 */
callApi.interceptors.response.use(
    function(response) {
        return response;

    },
    function(error) {
        return Promise.reject(error);
    }
);

export default callApi;
