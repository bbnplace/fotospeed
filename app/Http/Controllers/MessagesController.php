<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessagesController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Inbox/List', [
            'endpoint' => route('messages.records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
    {
        $messages = [];
        $messagesCount = 0;

        $page = $request->page;
        $messagesPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Message::query();

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
               $query->where('source', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('receiver', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('message', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $messagesCount = $query->count();
        $messages = $query->take($messagesPerPage)
            ->skip($messagesPerPage * ($page - 1))
            ->get();

        return [
            'records' => $messages,
            'totalRecords' => $messagesCount,
        ];
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Message::whereIn('id', $request->ids)->delete();

            return redirect()->route('messages')->with('note', 'Selected messages have been deleted');
        }
    }
}
