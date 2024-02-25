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
        return view('content.journal',);
    }

    /**
     * get journal list
     */
    public function getJournal($year, $month)
    {
        $searchKey = $year . '-' . $month . '%';

        $journals = Journal::where('journals.user_id', '=', auth()->user()->id)
            ->where('journals.date', 'like', $searchKey)
            ->with('journal_photos')
            ->orderBy('date', 'asc')
            ->get();

        $type = 'add';
        $data = $this->transform($journals, $type);

        $res['year'] = $year;
        $res['hebrewYear'] = 5783 + (intval($year) - 2024);
        $res['month'] = $month;
        $res['data'] = $data;


        return Response::json($res);
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
            'link' => 'nullable'
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
        $journal->photo_link = isset($validated['link']) ? $validated['link'] : null;
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

        for ($i = 1; $i <= 3; $i++) {
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
            return;
        }
    }


    /**
     * show the form for editing the specified resource
     */
    public function edit($id)
    {
        $journals = Journal::where('id', $id)->with('journal_photos')->get();
        $type = 'edit';
        $data = $this->transform($journals, $type);

        return response::json($data);
    }



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
            'link' => 'url | nullable'
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

        for ($i = 1; $i <= 3; $i++) {
            $photoKey = 'photo' . $i;
            $desKey = 'des' . $i;
            $type = 'journal';
            $file = $request->file($photoKey) ?? null;
            $argArr = ['journal_id' => $id, 'name' => $photoKey];

            if (!is_null($file) && !array_key_exists($desKey, $photoData)) {
                Journal_photo::updateOrCreate(
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
                    ['description' => $photoData[$desKey], 'url' => $this->ImgProcessing($file, $type), 'journal_id' => $id, 'name' => $photoKey]
                );
            }
        }

        return response::json(['status_code' => 200, 'message' => 'update success']);
    }


    protected function transform($journals, $type)
    {
        $journals->transform(function ($journal) use (&$type) {
            return [
                'id' => $journal->id,
                'date' => $type == "add" ? date('m/d', strtotime($journal->date)) : $journal->date,
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



    protected function message()
    {
        return [
            'required' => ':attribute為必填',
            'string' => ':attribute必須為文字',
            'min:30' => ':attribute字數需大於30字',
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