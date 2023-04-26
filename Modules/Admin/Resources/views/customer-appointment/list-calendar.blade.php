<div class="list-calendar load_ajax" id="m_calendar">

</div>
@if(in_array('admin.customer_appointment.submitModalAdd',session('routeList')))
    <input type="hidden" id="role-add-appointments" value="1">
@else
    <input type="hidden" id="role-add-appointments" value="0">
@endif


