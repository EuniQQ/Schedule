<?php

namespace  App\Http\Traits;

trait UploadImgTrait
{
    /**
     * 上傳照片、搬移檔案、回傳檔案位置
     * $type = "sticker" | "mainImg" | "headerImg" | "footerImg | journal(日記)"
     */
    protected function ImgProcessing($file, $type)
    {
        $imgName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(
            public_path('storage/img/' . $type),
            $imgName
        );

        $imgUri = "/storage/img/" . $type . "/" . $imgName;
        return $imgUri;
    }
}
