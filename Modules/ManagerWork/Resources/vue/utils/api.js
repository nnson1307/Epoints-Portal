import callApi from '@/utils/api-abstract';


// Lấy list cấu hình search
export function getSearchOptionApi() {
    return callApi.get('/manager-work-api/search-options');
}

// Lấy danh sách công việc
export function getListWorkApi(data) {
    return callApi.post('/manager-work-api/list', data);
}

// Cập nhật trạng thái công việc
export function updateWorkStatusApi(data) {
    return callApi.post('/manager-work/change-status', {
        manage_work_id: data.manage_work_id, 
        manage_status_id: data.manage_status_id
    });
}

// Load Process
export function showProcessApi(data) {
    return callApi.post('/manager-work/load-form-update-process', {
        manage_work_id: data.manage_work_id
    });
}