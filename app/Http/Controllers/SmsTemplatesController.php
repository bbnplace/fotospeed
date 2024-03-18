<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SmsTemplatesController extends Controller
{
    protected $rules = [
        'name' => 'required|string|unique:groups,name|min:2|max:64',
        'template' => 'required|string|min:1|max:1530'
    ];

    public function index()
    {
        return Inertia::render('Backend/SmsTemplate/List', [
            'endpoint' => route('sms-templates.records')
        ]);
    }

    public function records(Request $request)
        {
            $smsTemplates = [];
            $smsTemplatesCount = 0;

            $page = $request->page;
            $smsTemplatesPerPage = $request->itemsPerPage;
            $sortBys = $request->sortBy;
            $search = $request->search;

            $query = SmsTemplate::query();

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

            $smsTemplatesCount = $query->count();
            $smsTemplates = $query->take($smsTemplatesPerPage)
                ->skip($smsTemplatesPerPage * ($page - 1))
                ->get();

            return [
                'records' => $smsTemplates,
                'totalRecords' => $smsTemplatesCount,
            ];
        }

    public function add()
    {
        return Inertia::render('Backend/SmsTemplate/Add', [
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        // Save the record to database
        SmsTemplate::create($validated);

        return redirect()->route('sms-templates')->with('status', 'SMS Template Created');
    }


    public function edit($ref)
    {
        $smsTemplate = SmsTemplate::where('id', $ref)->first();
        return Inertia::render('Backend/SmsTemplate/Edit', [
            'smsTemplate' => $smsTemplate,
        ]);
    }
}
