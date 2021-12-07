<?php

namespace App\Libraries;

use App\Models\FileModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Intervention\Image\Facades\Image as ImgMap;

class FilesLibrary
{
    private $rootDir = 'public/';

    public function saveUserImg($image, $dir)
    {
        $id = Uuid::uuid4()->toString();
        $resize = $this->resize($image, 128, 128);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        $modelImage = new FileModel();
        $modelImage->id = $id;
        $modelImage->module_type = 'user';
        $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        $modelImage->path = $this->rootDir . $dir;
        $modelImage->file_url = $full;
        $modelImage->created_at = time();
        $modelImage->save();
        $imageId = $modelImage->id;
        return $imageId;
    }

    public static function delete(FileModel $model)
    {
        Storage::disk()->delete($model->file_url);
    }

    public function resize($raw, $standardWidth = 750, $standardHeight = 410)
    {
        $image = ImgMap::make($raw);
        $image = $image->resize($standardWidth, $standardHeight);
        return $image;
    }
}
