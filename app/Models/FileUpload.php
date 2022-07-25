<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends Model
{
    use HasFactory;

    public function AddMedia($file, $folder,$hasRoute = 'insert', $oldFile = '')
    {
        # code...
        if($hasRoute == 'insert'){
            $path = Storage::disk('public')->put($folder, $file);
        }else{
            if(!empty($oldFile)){
                Storage::disk('public')->delete($folder.'/'.$oldFile);
            }
            $path = Storage::disk('public')->put($folder, $file);
        }

        return basename($path);
    }

    public function deleteMedia($folder,$file)
    {
        # code...
        return Storage::disk('public')->delete($folder.'/'.$file);

    }
}
