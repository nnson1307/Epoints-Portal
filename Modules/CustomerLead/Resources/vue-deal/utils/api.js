import callApi from '@/utils/api-abstract';


// Lấy list cấu hình search
export function getSearchOptionApi() {
    return callApi.get('/customer-lead/customer-deal/search-options');
}

// Lấy danh sách công việc
export function getListCustomerLeadApi(data) {
    return callApi.post('/customer-lead/customer-deal/load-kanban', data);
}

// Cập nhật trạng thái lead
export function updateJourneyApi(data) {
    return callApi.post('/customer-lead/customer-deal/update-journey', data);
}

// Load Process
export function showModalCallApi(data) {
    return callApi.post('/customer-lead/customer-deal/modal-call', data);
}

//Xem chi tiết KH Tiềm năng
export function showModalCustomerLeadDetailApi(data) {
    return callApi.post('/customer-lead/customer-lead/show', data);
}

export function deleteDealApi(data) {
    return callApi.post('/customer-lead/customer-deal/destroy', data);
}

export function editCustomerLeadApi(data) {
    return callApi.post('/customer-lead/customer-lead/edit', data);
}

export function popupCustomerCareApi(data) {
    return callApi.post('/customer-lead/customer-lead/popup-customer-care', data);
}