<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Ngoc Son
 * Date: 3/26/2018
 * Time: 4:33 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\StoreTable;
use Modules\Admin\Repositories\District\DistrictRepositoryInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Admin\Repositories\Store\StoreRepositoryInterface;

use Modules\Admin\Repositories\Ward\WardRepositoryInterface;





class StoreController extends Controller
{
    protected $stores;
    protected $province;
    protected $district;
    protected $ward;
    protected $excel;



    public function __construct(StoreRepositoryInterface $stores,ProvinceRepositoryInterface $province,
                                DistrictRepositoryInterface $district,WardRepositoryInterface $ward,
                                \Maatwebsite\Excel\Excel $excel)
    {
        $this->stores=$stores;
        $this->province=$province;
        $this->district=$district;
        $this->ward=$ward;
        $this->excel=$excel;
    }

    //return view index
    public function indexAction()
    {
        $storeList=$this->stores->list();

        return view('admin::store.index',[
            'LIST'=>$storeList,
            'FILTER'=>$this->filters()
        ]);
    }
    //function filter
    protected function filters()
    {
        return[
            'is_active'=>[
                'text'=>__('Trạng thái'),
                'data'=>[
                    ''=>'Tất cả',
                    1=>'Đang hoạt động',
                    0=>'Tạm đóng'
                ]
            ],
            'province_id'=>[
                'text'=>'Tỉnh/Thành',
                'data'=>[
                    ''=>'Tất cả'

                ]
            ]

        ];
    }

    /**
     * Ajax danh sách Store
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters=$request->only(['page','display','search_type','search_keyword','is_active','province_id']);
        $storeList= $this->stores->list($filters);

        return view('admin::store.list',['LIST' => $storeList]);

    }

    //return view add
    public function addAction()
    {
        $optionProvince=$this->province->getOptionProvince();

        return view('admin::store.add',array(
            'optionProvince'=>$optionProvince
        ));
    }


    //submit add
    public function submitAddAction(Request $request)
    {
        $data=$this->validate($request,[
           'store_name'=>'required|unique:stores',
            'address'=>'required',
            'province_id'=>'required',
            'district_id'=>'required',
            'ward_id'=>'required',

        ],[
            'store_name.required'=>'Tên chi nhánh không được bỏ trống',
            'store_name.unique'=>'Tên chi nhánh đã tồn tại',
            'address.required'=>'Vui lòng nhập tên đường , số nhà',

        ]);
//        dd($request->input('store_image'));
        $data['store_image']=$this->transferTempfileToAdminfile($request->input('store_image'));

        $this->stores->add($data);

        return redirect()->route('admin.store');
    }

    //function upload image
    public function uploadsAction(Request $req){
        $this->validate($req,[
           "store_image"=>"mimes:jpg,jpeg,png,gif|max:10000"
        ],[
            "store_image.mimes"=>"File này không phải file hình",
            "store_image.max"=>"File quá lớn"
        ]);
      $file= $this->uploadImageTemp($req->file('store_image'));
        return response()->json(["file"=>$file,"success"=>"1"]);
    }

    //Lưu file image vào folder temp
    private function uploadImageTemp($file)
    {

        $file_name= time()."_stores.".$file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH. "/" .$file_name, file_get_contents($file));
        return  $file_name;
    }

    //Chuyển file từ folder temp sang folder chính
    private function transferTempfileToAdminfile($filename)
    {
        $old_path=TEMP_PATH.$filename;
        $new_path=STORE_UPLOADS_PATH.date('Ymd').'/'.$filename;
        Storage::disk('public')->makeDirectory(STORE_UPLOADS_PATH.date('Ymd'));
        Storage::disk('public')->move($old_path,$new_path);
        return $new_path;
    }

    //function delete image
    public function deleteTempFileAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH.$request->input('filename'));
        return response()->json(['success'=>'1']);
    }



    //function remove
    public function removeAction($id)
    {
        $this->stores->remove(($id));
        return response()->json([
            'error'=>0,
            'message'=>'Remove success'
        ]);
    }

    //return view edit
    public function editAction($id)
    {
        $item=$this->stores->getItem($id);
       //Show province , district , ward khi edit
        $oOptionProvince=$this->province->getOptionProvince();
        $oOptionDistrict=$this->district->getOptionDistrict($item->province_id);
        $oOptionWard=$this->ward->getOptionWard($item->district_id);

        return view('admin::store.edit',compact('item'),array(
           'optionProvince'=>$oOptionProvince,
            'optionDistrict'=>$oOptionDistrict,
            'optionWard'=>$oOptionWard
        ));


    }
    //submit edit
    public function submitEditAction(Request $request,$id)
    {
        $data = $this->validate($request,[
           'store_name'=>'required|unique:stores,store_name,'.$id.",store_id",
            'address'=>'required',
            'province_id'=>'required',
            'district_id'=>'required',
            'ward_id'=>'required'

        ],[
            'store_name.required'=>'Tên chi nhánh không được để trống',
            'store_name.unique'=>'Tên chi nhánh đã tồn tại' ,
            'province_id.required'=>'Tỉnh thành không được để trống',
            'district_id.required'=>'Quận huyện không được để trống',
            'ward_id.required'=>'Phường xã không được để trống'
        ]);
        $data['store_image'] = $this->transferTempfileToAdminfile($request->input('store_image'));

        $storeEdit=$this->stores->edit($data,$id);
        if($storeEdit)
        {
            $request->session()->flash('status','Cập nhật thành công');
        }
        else{
            Session::flash('messages','Cập nhật thất bại');
            return redirect()->back();
        }
        return redirect()->route('admin.store');

    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $params=$request->all();
        $data['is_active']=($params['action']=='unPublish') ? 1:0;
        $this->stores->edit($data,$params['id']);
        return response()->json([
            'status'=>0
        ]);
    }

    //export Excel
    public function exportAction(Request $request)
    {
        $params = $request->except("_token");

        foreach ($params as $key=>$value)
        {
            $oExplode=explode(",",$value);
            $column[]= $oExplode[0];
            $title[]=$oExplode[1];
        }
        $this->stores->exportExcel($column,$title);
    }





    public function importAction()
    {
       return view('admin::store.import-excel');
    }

    public function submitImportAction(Request $request)
    {

        return $this->stores->uploadExcel($request);
    }


}