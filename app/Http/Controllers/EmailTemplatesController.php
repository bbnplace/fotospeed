<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailTemplatesController extends Controller
{

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
        $validated = $request->validate([
            'name' => 'required|string|unique:email_templates,name|min:2|max:64',
            'template' => 'required|string|min:1|max:1530'
        ]);

        // Save the record to database
        EmailTemplate::create($validated);

        return redirect()->route('email-templates')->with('status', 'Email Template Created');
    }

    private function getEmailTemplate($templateId)
    {
        $emailTemplate = EmailTemplate::where('id', $templateId)->first();

        return [
            'emailTemplate' => $emailTemplate,
        ];
    }

    public function update(Request $request, $id)
    {
        $emailTemplate = EmailTemplate::where('id', $id)->first();
        if(empty($emailTemplate)){
            return redirect()->route('email-templates')->with('note', 'Select the email template you want to edit');
        }

        // In the event where the template name changed, user should be notified
        $request->validate([
            'name' => $emailTemplate->name != $request->name ? 'required|string|unique:email_templates,name|min:2|max:64' : 'required|string|min:2|max:64',
            'template' => 'required|string|min:1|max:1530'
        ]);

        $emailTemplate->name = $request->name;
        $emailTemplate->template = $request->template;
        $emailTemplate->save();

        return redirect()->route('email-template.view', [$id])->with('note', 'Updated.');
    }


    public function edit($id)
    {
        return Inertia::render('Backend/EmailTemplate/Edit', $this->getEmailTemplate($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/EmailTemplate/Detail', $this->getEmailTemplate($id));
    }
}
