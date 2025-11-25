<?php

namespace App\Http\Controllers;

use App\Config\TemplateItem;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Services\EmailProviderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class EmailTemplatesController extends Controller
{

    public function index()
    {
        $settings = Setting::first();
        return Inertia::render('Backend/EmailTemplate/List', [
            'endpoint' => route('email-templates.records'),
            'syncEndpoint' => route('email-templates.sync'),
            'emailProvider' => $settings->email_api_provider ?? null,
            'emailMethod' => $settings->email_method ?? 'SMTP',
            'note' => session('note')
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
            'usage' => TemplateItem::usage(),
            'targets' => TemplateItem::target(),
            'timings' => TemplateItem::timing(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:email_templates,name|min:2|max:64',
            'template' => 'required|string|min:1|max:1530',
            'usage' => ['nullable', Rule::in(TemplateItem::usage())],
            'timing' => ['nullable', Rule::in(TemplateItem::timing())],
            'target' => ['nullable', Rule::in(TemplateItem::target())],
        ]);

        // Save the record to database
        EmailTemplate::create($validated);

        return redirect()->route('email-templates')->with('note', 'Email Template Created');
    }

    private function getEmailTemplate($templateId)
    {
        $emailTemplate = EmailTemplate::where('id', $templateId)->first();

        return [
            'emailTemplate' => $emailTemplate,
            'usage' => TemplateItem::usage(),
            'targets' => TemplateItem::target(),
            'timings' => TemplateItem::timing(),
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
            'template' => 'required|string|min:1|max:1530',
            'usage' => ['nullable', Rule::in(TemplateItem::usage())],
            'timing' => ['nullable', Rule::in(TemplateItem::timing())],
            'target' => ['nullable', Rule::in(TemplateItem::target())],
        ]);

        $emailTemplate->name = $request->name;
        $emailTemplate->template = $request->template;
        $emailTemplate->usage = $request->usage;
        $emailTemplate->timing = $request->timing;
        $emailTemplate->target = $request->target;
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

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            // TODO: Check if the selected template is linked with a process or setting. 
            // If template is linked, do not delete. User should be requested to unlink
            // the template before proceeding.
            
            EmailTemplate::whereIn('id', $request->ids)->delete();
            return redirect()->route('email-templates')->with('note', 'Selected email templates have been deleted');
        }
    }

    public function syncProviderTemplates()
    {
        $service = new EmailProviderService();
        $result = $service->fetchProviderTemplates();

        if ($result['success']) {
            return response()->json([
                'status' => 'success',
                'message' => $result['message'],
                'count' => $result['count'] ?? 0
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $result['message']
            ], 400);
        }
    }
}
