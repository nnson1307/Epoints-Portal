<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\CustomerLead\Models\ConfigTable;

class Helper
{

    static function isUpdate($data){

        
        if( ( Auth()->id() == $data['staff_id_level1'] && $data['staff_id_level1'] == null)
            || ( Auth()->id() == $data['staff_id_level2'] && $data['staff_id_level2'] == null)
            || ( Auth()->id() == $data['staff_id_level3'] && $data['staff_id_level3'] == null) ){
            return 1;
        }
        return 0;
    }

    static function formatDate($data, $format = 'd/m/Y'){
        if(!$data){
            return;
        }

        $data = str_replace('/', '-', $data);
        return Carbon::parse($data)->format($format);
    }

    static function formatDateTime($data){
        if(!$data){
            return;
        }

		$data = str_replace('/', '-', $data);
        return Carbon::parse($data)->format('d/m/Y - H:i');
    }

    static function getAgoTime($date){
        if(!$date){
            return;
        }

        $mConfig = new ConfigTable();
        $config = $mConfig->getInfoByKey('lang_site');
        $locate = $config ? $config->value : 'vi';
		Carbon::setLocale($locate);
	    $dt = Carbon::createFromFormat('Y-m-d H:i:s', $date);
	    $now = Carbon::now();
	    return $dt->diffForHumans($now,
            ['skip' => ['week']]
        ); 
	}


    static function isActive($data){
        if( Auth()->id() == $data['staff_id']){
            return 1;
        }
        return 0;
    }

    public static function uploadFile($file, $path){
        if(!$file){
            return null;
        }

        //get filename with extension
        $filenamewithextension = $file->getClientOriginalName();

        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        $filename = Str::slug($filename);
        //get file extension
        $extension = $file->getClientOriginalExtension();

        //filename to store
        $filenametostore = $filename . '_' . uniqid() . '.' . $extension;

        Storage::put('/public/' . $path . '/' . $filenametostore, fopen($file, 'r+'));

        return [
            'name' => $filenametostore,
            'path' => 'storage/' . $path . '/' . $filenametostore
        ];
    }

    static function checkIsAdmin(){
        $user = Auth::user();
        return $user->is_admin;
    }
}
