<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/11/2018
 * Time: 4:08 PM
 */

namespace Modules\Admin\Repositories\UploadImage;

use Illuminate\Support\Facades\Storage;

class UploadImageRepository implements UploadImageRepositoryInterface
{

    public function uploadSingleFile($file)
    {
        try {
            // TODO: Implement uploadSingleFile() method.
            $file_name = (time() + random_int(0, 90)) . "_servicecard." . $file->getClientOriginalExtension();
//            array_push($arr_file_name, $file_name);
            Storage::disk('public')->put(public_path("uploads") . "/" . TEMP_PATH . "/" .$file_name, file_get_contents($file));
            return response()->json(["status"=>"success","value"=>$file_name]);
        }catch(\Exception $e){
            return response()->json(["status"=>"error","value"=>$e->getMessage()]);
        }
    }

    public function deleteTempImage($file)
    {
        // TODO: Implement deleteTempImage() method.
        try {
                Storage::disk("uploads")->delete(TEMP_PATH . "/" . $file);
                return response()->json(["status" => "success", "value" => $file]);
        }catch (\Exception $e){
            return response()->json(["status"=>"error","value"=>$e->getMessage()]);
        }
    }

    public function moveFromTemp($files)
    {
        $file = $files->input("image");
        $old_path = TEMP_PATH ."/". $file;
        $new_path = SERVICE_CARD_PATH . date('Ymd') . '/' . $file;
        // TODO: Implement moveFromTemp() method.
        Storage::disk('uploads')->makeDirectory(SERVICE_CARD_PATH."/" . date('Ymd'), 0777);
        Storage::disk('uploads')->move($old_path, $new_path);
        return $new_path;
    }

}