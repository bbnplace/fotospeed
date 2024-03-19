<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailTemplatesController extends Controller
{
    protected $rules = [
        'name' => 'required|string|unique:groups,name|min:2|max:64',
        'template' => 'required|string|min:1|max:1530'
    ];

    public function index()
    {
        return Inertia::render('Backend/EmailTemplate/List', [
            'endpoint' => route('email-templates.records')
        ]);
    }

    public function records(Request $request)
        {
            $emailTemplates = [];
            $emailTemplatesCount = 0;

            $page = $request->page;
            $emailTemplatesPerPage = $request->itemsPerPage;
            $sortBys = $request->sortBy;
            $search = $request->search;

            $query = EmailTemplate::query();

            if (!empty($search)) {
                $searchTerm = $search['_value'];
                if (!empty($searchTerm)) {
                   $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
                   $query->where('template', 'LIKE', sprintf('%%%s%%', $searchTerm));
                }
            }

            if (!empty($sortBys)) {
                foreach ($sortBys as $sortBy) {
                    $query->orderBy($sortBy['key'], $sortBy['order']);
                }
            }else{
                $query->orderBy('id', 'desc');
            }

            $emailTemplatesCount = $query->count();
            $emailTemplates = $query->take($emailTemplatesPerPage)
                ->skip($emailTemplatesPerPage * ($page - 1))
                ->get();

            return [
                'records' => $emailTemplates,
                'totalRecords' => $emailTemplatesCount,
            ];
        }

    public function add()
    {
        return Inertia::render('Backend/EmailTemplate/Add', [
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        // Save the record to database
        EmailTemplate::create($validated);

        return redirect()->route('email-templates')->with('status', 'Email Template Created');
    }


    public function edit($ref)
    {
        $emailTemplate = EmailTemplate::where('id', $ref)->first();
        return Inertia::render('Backend/EmailTemplate/Edit', [
            'emailTemplate' => $emailTemplate,
        ]);
    }
}
