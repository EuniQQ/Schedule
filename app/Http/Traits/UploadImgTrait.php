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
        $imgName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(
            public_path('./images/' . $type),
            $imgName
        );

        $imgUri = "./images/" . $type . "/" . $imgName;
        return $imgUri;
    }
}
