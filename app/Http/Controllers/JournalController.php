<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Journal;
use App\Models\Journal_photo;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\UploadImgTrait;

class JournalController extends Controller
{
    use UploadImgTrait;

    public function index()
    {
        return view('content.journal');
    }



    /**
     * get journal list
     */
    public function getJournal($year, $month)
    {

        $query = Journal::where('user_id', auth()->user()->id);

        $dates = $query->orderBy('date', 'desc')->pluck('date')->all();
        $yearList = [];
        foreach ($dates as $date) {
            $yearOpt = date('Y', strtotime($date));
            $yearList[] = $yearOpt;
        }

        $searchKey = $year . '-' . $month . '%';
        $journals = $query->where('date', 'like', $searchKey)
            ->with('journal_photos')
            ->orderBy('date', 'asc')
            ->get();

        $type = 'index';
        $data = $this->transform($journals, $type);

        $res['year'] = $year;
        $res['hebrewYear'] = 5783 + (intval($year) - 2024);
        $res['month'] = $month;
        $res['data'] = $data;
        $res['yearList'] = array_unique($yearList);
        return Response::json($res);
    }



    /**
     * keyword search
     */
    public function search(Request $request)
    {
        $_data = $request->keyword;
        $keyword = '%' . $_data . '%';

        $journals = Journal::where('user_id', auth()->user()->id)
            ->where('title', 'like', $keyword)
            ->orWhere('content', 'like', $keyword)
            ->orWhereHas('journal_photos', function ($query) use ($keyword) {
                $query->where('description', 'like', $keyword);
            })
            ->with('journal_photos')
            ->orderBy('date', 'asc')
            ->get();

        $type = "search";
        $data = $this->transform($journals, $type);

        return Response::json(["data" => $data]);
    }



    /**
     * add a journal
     */
    public function create(Request $request)
    {

        $rule = [
            'date' => 'required | date',
            'title' => 'required | string',
            'content' => 'required | min:30',
            'photo1 ' => 'file | nullable',
            'photo2 ' => 'file | nullable',
            'photo3 ' => 'file | nullable',
            'photo4 ' => 'file | nullable',
            'des1' => 'string | nullable',
            'des2' => 'string | nullable',
            'des3' => 'string | nullable',
            'des4' => 'string | nullable',
            'photo_link' => 'nullable'
        ];


        $_data = $request->post();
        $validator = Validator::make($_data, $rule, $this->message(), $this->attribute());
        if ($validator->fails()) {
            $error = $this->ifValidateFails($validator);
            return Response(['message' => $error], 422);
        }

        $validated = $validator->validated();
        $journal = new Journal;
        $journal->user_id = auth()->user()->id;
        $journal->date = $validated['date'];
        $journal->title = $validated['title'];
        $journal->content = $validated['content'];
        $journal->photo_link = isset($validated['photo_link']) ? $validated['photo_link'] : null;
        $journal->save();

        $journalId = $journal->id;
        if ($request->hasAny(['photo1', 'photo2', 'photo3', 'photo4'])) {
            $this->handleJournalImg($request, $journalId);
        }

        return Response::json(['message' => 'ok']);
    }


    protected function handleJournalImg($request, $journalId)
    {
        $_data = $request->post();
        for ($i = 1; $i <= 4; $i++) {
            $name = 'photo' . $i;

            if ($request->hasFile($name)) {
                $type = 'journal';
                $file = $request->File($name);
                $jnPhoto = new Journal_photo;
                $jnPhoto->journal_id = $journalId;
                $jnPhoto->name = $name;
                $jnPhoto->url = $this->ImgProcessing($file, $type);
                $desName = "des" . $i;
                $jnPhoto->description = isset($_data[$desName]) ? $_data[$desName] : null;
                $jnPhoto->save();
            }
        }
        return;
    }



    /**
     * show the form for editing the specified resource
     */
    public function edit($id)
    {
        $data = $this->getEditingData($id);

        return response::json($data);
    }


    /**
     * update journal
     */
    public function update(Request $request, $id)
    {
        $request->all();
        $rule = [
            'date' => 'date | nullable',
            'title' => 'string | nullable',
            'content' => 'min:30 | nullable',
            'photo1 ' => 'file | nullable',
            'photo2 ' => 'file | nullable',
            'photo3 ' => 'file | nullable',
            'photo4 ' => 'file | nullable',
            'des1' => 'string | nullable',
            'des2' => 'string | nullable',
            'des3' => 'string | nullable',
            'des4' => 'string | nullable',
            'photo_link' => 'url | nullable'
        ];

        $_data = $request->post();
        $validator = Validator::make($_data, $rule, $this->message(), $this->attribute());
        if ($validator->fails()) {
            $error = $this->ifValidateFails($validator);
            return Response(['message' => $error], 422);
        }

        $validated = $validator->validated();

        // 更新 DB Journals
        $textData = array_filter($_data, function ($key) {
            return strpos($key, "photo") !== 0 && strpos($key, "des") !== 0;
        }, ARRAY_FILTER_USE_KEY);

        if (!empty($textData)) {
            Journal::where('id', $id)
                ->where('user_id', auth()->user()->id)
                ->update($textData);
        };

        // 更新 DB Journal_photos
        $photoData = array_filter($request->allFiles(), function ($key) {
            return strpos($key, 'photo') === 0 || strpos($key, 'des') === 0;
        }, ARRAY_FILTER_USE_KEY);

        for ($i = 1; $i <= 4; $i++) {
            $photoKey = 'photo' . $i;
            $desKey = 'des' . $i;
            $type = 'journal';
            $file = $request->file($photoKey) ?? null;

            $argArr = ['journal_id' => $id, 'name' => $photoKey];

            if (!is_null($file) && !array_key_exists($desKey, $photoData)) {
                $res = Journal_photo::updateOrCreate(
                    $argArr,
                    ['url' => $this->ImgProcessing($file, $type)]
                );
            } elseif (is_null($file) && array_key_exists($desKey, $photoData)) {
                Journal_photo::updateOrCreate(
                    $argArr,
                    ['description' => $photoData[$desKey]]
                );
            } elseif (!is_null($file) && array_key_exists($desKey, $photoData)) {
                Journal_photo::updateOrCreate(
                    $argArr,
                    ['description' => $photoData[$desKey], 'url' => $this->ImgProcessing($file, $type)]
                );
            }
        }

        return response::json(['status_code' => 200, 'message' => 'update success']);
    }


    /**
     * delete one piece of img of edit modal 
     */
    public function deleteImg(Request $request, $id)
    {
        $_data = $request->post();
        $journalId = $_data['journalId'];
        $img = Journal_photo::find($id);
        if ($img) {
            $img->delete();
            $path = $img->getOriginal('url');
            $this->delImgFromFolder($path);
            $data = $this->getEditingData($journalId);
            return response::json($data);
        } else {
            abort("Not found The Picture", 404);
        }
    }


    /**
     * delete journal
     */
    public function destroy($id)
    {

        $journal = auth()->user()->journals->find($id);
        if ($journal) {
            // delete journal photos & photo where's in folder
            $journalPhotos = $journal->journal_photos;
            if ($journalPhotos) {
                foreach ($journalPhotos as $jourPhoto) {
                    if ($jourPhoto->delete()) {
                        $path = $jourPhoto->getOriginal('url');
                        $this->delImgFromFolder($path);
                    }
                }
            }

            $journal->delete();
            return Response::json(['message' => '刪除成功']);
        } else {
            abort(404, "Not found !");
        }
    }



    public function getPhotoDes($id)
    {
        $photo = Journal_photo::find($id);
        $des = $photo->description;
        $src = $photo->url;
        return Response::json(['description' => $des, 'src' => $src]);
    }



    protected function transform($journals, $type)
    {
        $journals->transform(function ($journal) use (&$type) {
            return [
                'id' => $journal->id,
                'date' => $type == "index" ? date('m/d', strtotime($journal->date)) : $journal->date,
                'title' => $journal->title,
                'content' => $journal->content,
                'photosLink' => $journal->photo_link,
                'photos' => $journal->journal_photos->map(function ($photo) {
                    return [
                        'photo_id' => $photo->id,
                        'url' => $photo->url,
                        'description' => $photo->description,
                        'name' => $photo->name
                    ];
                })
            ];
        });

        return $journals;
    }



    protected function getEditingData($id)
    {
        $journals = Journal::where('id', $id)->with('journal_photos')->get();
        $type = 'edit';
        $data = $this->transform($journals, $type);

        return $data;
    }


    protected function message()
    {
        return [
            'required' => ':attribute為必填',
            'string' => ':attribute必須為文字',
            'min' => ':attribute字數需大於30字',
            'date' => ':attribute必須為日期格式',
            'file' => ':attribute必須為檔案格式'
        ];
    }

    protected function attribute()
    {
        return [
            'date' => '日期',
            'title' => '標題',
            'content' => '內文',
            'photo1' => '照片1',
            'photo2' => '照片2',
            'photo3' => '照片3',
            'photo4' => '照片4',
            'des1' => '圖1的照片敘述',
            'des2' => '圖2的照片敘述',
            'des3' => '圖3的照片敘述',
            'des4' => '圖4的照片敘述',
        ];
    }
}
