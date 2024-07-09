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
            'endpoint' => route('sms-templates.records'),
            'note' => session('note')
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
                   $query->where(function ($query) use ($searchTerm){
                        $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm))
                              ->orWhere('template', 'LIKE', sprintf('%%%s%%', $searchTerm));
                   });
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

        return redirect()->route('sms-templates')->with('note', 'SMS Template Created');
    }

    private function getSmsTemplate($templateId)
    {
        $smsTemplate = SmsTemplate::where('id', $templateId)->first();
        return [
            'smsTemplate' => $smsTemplate,
        ];
    }

    public function update(Request $request, $id)
    {
        $smsTemplate = SmsTemplate::where('id', $id)->first();
        if(empty($smsTemplate)){
            return redirect()->route('sms-templates')->with('note', 'Select the sms template you want to edit');
        }

        // In the event where the template name changed, user should be notified
        $request->validate([
            'name' => $smsTemplate->name != $request->name ? 'required|string|unique:sms_templates,name|min:2|max:64' : 'required|string|min:2|max:64',
            'template' => 'required|string|min:1|max:1530'
        ]);

        $smsTemplate->name = $request->name;
        $smsTemplate->template = $request->template;
        $smsTemplate->save();

        return redirect()->route('sms-template.view', [$id])->with('note', 'Updated.');
    }

    public function edit($id)
    {
        return Inertia::render('Backend/SmsTemplate/Edit', $this->getSmsTemplate($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/SmsTemplate/Detail', $this->getSmsTemplate($id));
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            SmsTemplate::whereIn('id', $request->ids)->delete();

            return redirect()->route('sms-templates')->with('note', 'Selected sms templates have been deleted');
        }
    }
}
