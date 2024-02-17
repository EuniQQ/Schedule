<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index()
    {
        return view('content.journal',);
    }

    public function getJournal($year, $month)
    {
        $searchKey = $year . '-' . $month . '%';

        $journals = Journal::where('journals.user_id', '=', auth()->user()->id)
            ->where('journals.date', 'like', $searchKey)
            ->with('journal_photos')
            ->orderBy('date', 'asc')
            ->get();

        $data = $journals->transform(function ($journal) {
            return [
                'id' => $journal->id,
                'date' => date('m/d', strtotime($journal->date)),
                'title' => $journal->title,
                'content' => $journal->content,
                'photosLink' => $journal->photo_link,
                'photos' => $journal->journal_photos->map(function ($photo) {
                    return [
                        'photo_id' => $photo->id,
                        'url' => $photo->url,
                        'description' => $photo->description,
                    ];
                })
            ];
        });

        $res['year'] = $year;
        $res['hebrewYear'] = 5783 + (intval($year) - 2024);
        $res['month'] = $month;
        $res['data'] = $data;
        

        return Response::json($res);
    }
}
