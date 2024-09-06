<?php

namespace App\Http\Controllers;

use App\Config\TemplateItem;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class NotificationTemplatesController extends Controller
{
    protected $rules = [
        'name' => 'required|string|unique:notification_templates,name|min:2|max:64',
        'title' => 'required|string|min:2|max:64',
        'template' => 'required|string|min:1|max:1530'
    ];

    public function index()
    {
        return Inertia::render('Backend/NotificationTemplate/List', [
            'endpoint' => route('notification-templates.records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
        {
            $notificationTemplates = [];
            $notificationTemplatesCount = 0;

            $page = $request->page;
            $notificationTemplatesPerPage = $request->itemsPerPage;
            $sortBys = $request->sortBy;
            $search = $request->search;

            $query = NotificationTemplate::query();

            if (!empty($search)) {
                $searchTerm = $search['_value'];
                if (!empty($searchTerm)) {
                   $query->where(function ($query) use ($searchTerm){
                        $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm))
                            ->orWhere('title','LIKE', sprintf('%%%s%%', $searchTerm))
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

            $notificationTemplatesCount = $query->count();
            $notificationTemplates = $query->take($notificationTemplatesPerPage)
                ->skip($notificationTemplatesPerPage * ($page - 1))
                ->get();

            return [
                'records' => $notificationTemplates,
                'totalRecords' => $notificationTemplatesCount,
            ];
        }

    public function add()
    {
        return Inertia::render('Backend/NotificationTemplate/Add', [
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
        NotificationTemplate::create($validated);

        return redirect()->route('notification-templates')->with('note', 'Notification Template Created');
    }

    private function getNotificationTemplate($templateId)
    {
        $notificationTemplate = NotificationTemplate::where('id', $templateId)->first();
        return [
            'notificationTemplate' => $notificationTemplate,
            'usage' => TemplateItem::usage(),
            'targets' => TemplateItem::target(),
            'timings' => TemplateItem::timing(),
        ];
    }

    public function update(Request $request, $id)
    {
        $notificationTemplate = NotificationTemplate::where('id', $id)->first();
        if(empty($notificationTemplate)){
            return redirect()->route('notification-templates')->with('note', 'Select the Notification Template you want to edit');
        }

        // In the event where the template name changed, user should be notified
        $request->validate([
            'name' => $notificationTemplate->name != $request->name ? 'required|string|unique:notification_templates,name|min:2|max:64' : 'required|string|min:2|max:64',
            'title' => 'required|string|min:2|max:64',
            'template' => 'required|string|min:1|max:1530',
            'usage' => ['nullable', Rule::in(TemplateItem::usage())],
            'timing' => ['nullable', Rule::in(TemplateItem::timing())],
            'target' => ['nullable', Rule::in(TemplateItem::target())],
        ]);

        $notificationTemplate->name = $request->name;
        $notificationTemplate->title = $request->title;
        $notificationTemplate->template = $request->template;
        $notificationTemplate->usage = $request->usage;
        $notificationTemplate->timing = $request->timing;
        $notificationTemplate->target = $request->target;
        $notificationTemplate->save();

        return redirect()->route('notification-template.view', [$id])->with('note', 'Updated.');
    }

    public function edit($id)
    {
        return Inertia::render('Backend/NotificationTemplate/Edit', $this->getNotificationTemplate($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/NotificationTemplate/Detail', $this->getNotificationTemplate($id));
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            // TODO: Check if the selected template is linked with a process or setting. 
            // If template is linked, do not delete. User should be requested to unlink
            // the template before proceeding.
            NotificationTemplate::whereIn('id', $request->ids)->delete();
            return redirect()->route('notification-templates')->with('note', 'Selected Notification templates have been deleted');
        }
    }
}
