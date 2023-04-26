import moment from 'moment';

export default {
  isLoading: true,
  activeBoard: null,
  boards: [],
  searchOption: {},
  currentFilter: {
    search: '',
    pipeline_id: null,
    customer_type: null,
    select_manage_type_work_id: null,
    search_manage_type_work_id: null,
    dataField: null,
    date_from: moment().startOf('month').format('DD/MM/YYYY'),
    date_to: moment().endOf('month').format('DD/MM/YYYY'),
  },
}
