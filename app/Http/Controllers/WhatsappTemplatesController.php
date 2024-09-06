<?php

namespace App\Http\Controllers;

use App\Config\TemplateItem;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class WhatsappTemplatesController extends Controller
{
    protected $rules = [
        'name' => 'required|string|unique:whatsapp_templates,name|min:2|max:64',
        'template' => 'required|string|min:1|max:1530'
    ];

    public function index()
    {
        return inertia::render('Backend/WhatsappTemplate/List', [
            'endpoint' => route('whatsapp-templates.records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
        {
            $whatsAppTemplates = [];
            $whatsAppTemplatesCount = 0;

            $page = $request->page;
            $whatsAppTemplatesPerPage = $request->itemsPerPage;
            $sortBys = $request->sortBy;
            $search = $request->search;

            $query = WhatsappTemplate::query();

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

            $whatsAppTemplatesCount = $query->count();
            $whatsAppTemplates = $query->take($whatsAppTemplatesPerPage)
                ->skip($whatsAppTemplatesPerPage * ($page - 1))
                ->get();

            return [
                'records' => $whatsAppTemplates,
                'totalRecords' => $whatsAppTemplatesCount,
            ];
        }

    public function add()
    {
        return Inertia::render('Backend/WhatsappTemplate/Add', [
            'usage' => TemplateItem::usage(),
            'targets' => TemplateItem::target(),
            'timings' => TemplateItem::timing(),
        ]);
    }

    public function store(Request $request)
    {
        $this->rules['usage'] = ['nullable', Rule::in(TemplateItem::usage())];
        $this->rules['timing'] = ['nullable', Rule::in(TemplateItem::timing())];
        $this->rules['target'] = ['nullable', Rule::in(TemplateItem::target())];
        $validated = $request->validate($this->rules);

        // Save the record to database
        WhatsappTemplate::create($validated);

        return redirect()->route('whatsapp-templates')->with('note', 'WhatsApp Template Created');
    }

    private function getWhatsappTemplate($templateId)
    {
        $whatsAppTemplate = WhatsappTemplate::where('id', $templateId)->first();
        return [
            'whatsAppTemplate' => $whatsAppTemplate,
            'usage' => TemplateItem::usage(),
            'targets' => TemplateItem::target(),
            'timings' => TemplateItem::timing(),
        ];
    }

    public function update(Request $request, $id)
    {
        $whatsAppTemplate = WhatsappTemplate::where('id', $id)->first();
        if(empty($whatsAppTemplate)){
            return redirect()->route('whatsapp-templates')->with('note', 'Select the WhatsApp template you want to edit');
        }

        // In the event where the template name changed, user should be notified
        $request->validate([
            'name' => $whatsAppTemplate->name != $request->name ? 'required|string|unique:whatsapp_templates,name|min:2|max:64' : 'required|string|min:2|max:64',
            'template' => 'required|string|min:1|max:1530',
            'usage' => ['nullable', Rule::in(TemplateItem::usage())],
            'timing' => ['nullable', Rule::in(TemplateItem::timing())],
            'target' => ['nullable', Rule::in(TemplateItem::target())],
        ]);

        $whatsAppTemplate->name = $request->name;
        $whatsAppTemplate->template = $request->template;
        $whatsAppTemplate->usage = $request->usage;
        $whatsAppTemplate->timing = $request->timing;
        $whatsAppTemplate->target = $request->target;
        $whatsAppTemplate->save();

        return redirect()->route('whatsapp-template.view', [$id])->with('note', 'Updated.');
    }

    public function edit($id)
    {
        return Inertia::render('Backend/WhatsappTemplate/Edit', $this->getWhatsappTemplate($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/WhatsappTemplate/Detail', $this->getWhatsappTemplate($id));
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            // TODO: Check if the selected template is linked with a process or setting. 
            // If template is linked, do not delete. User should be requested to unlink
            // the template before proceeding.
            WhatsappTemplate::whereIn('id', $request->ids)->delete();
            return redirect()->route('whatsapp-templates')->with('note', 'Selected WhatsApp templates have been deleted');
        }
    }
}
