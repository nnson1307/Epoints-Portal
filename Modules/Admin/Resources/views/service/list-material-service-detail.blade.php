<table class="table table-striped m-table m-table--head-bg-default" id="table_service_accompanied">
    <thead class="bg">
    <tr>
        <th class="tr_thead_list" style="width: 5%">#</th>
        <th class="tr_thead_list width-350">{{__('Tên dịch vụ')}}</th>
        <th class="tr_thead_list text-center">{{__('Số lượng')}}</th>
    </tr>
    </thead>
    <tbody>
      @foreach($itemServiceMaterial as $key => $object)
          <tr class="accompanied_tb">
              <td>{{$key+1}}</td>
              <td class="product">
                  {{ $object['service_name'] }}
                  <input type="hidden" id="service_accompanied_hidden" name="service_accompanied_hidden" value="{{ $object['material_id'] }}">
                  <input type="hidden" id="service_code_accompanied_hidden" name="service_code_accompanied_hidden" value="{{ $object['material_code'] }}">
              </td>
              <td class="quantity text-center">
                  1
              </td>
          </tr>
      @endforeach
    </tbody>
</table>