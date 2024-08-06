<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Process;
use App\Models\Role;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Report\ReportBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcessesController extends Controller
{
    protected $rules = [
        'name' => 'string|required|unique:processes,name|min:2|max:64',
        'description' => 'nullable|string|min:24|max:1000',
    ];

    public function index(){
        return Inertia::render('Backend/Process/List', [
            'endpoint' => route('processes.records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
    {
        $processes = [];
        $processesCount = 0;

        $page = $request->page;
        $processesPerPage = $request->processesPerPage ?? 25;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Process::query();

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
               $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('description', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $processesCount = $query->count();
        $processes = $query->take($processesPerPage)
            ->skip($processesPerPage * ($page - 1))
            ->get();

        return [
            'records' => $processes,
            'totalRecords' => $processesCount,
        ];
    }

    public function add(){
        return Inertia::render('Backend/Process/Add', [
            'processes' => OrderStatus::getOrderStatusesArray(),
            'roles' => Role::getRolesArray(),
            'smsTemplates' => SmsTemplate::getSmsTemplatesArray(),
            'emailTemplates' => EmailTemplate::getEmailTemplatesArray(),
            'reportStates' => ReportBuilder::getReportStates(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        Process::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('processes')->with('note', $request->name . ' Process Registered');
    }

    private function getProcess($id)
    {
        $process = Process::find($id);

        return [
            'process' => $process,
        ];
    }

    public function edit($id)
    {
        return Inertia::render('Backend/Process/Edit', $this->getProcess($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/Process/Detail', $this->getProcess($id));
    }

    public function update(Request $request, $id)
    {
        $process = Process::find($id);
        if (empty($process)) {
            return redirect()->route('processes')->with('note', 'Select a process to edit.');
        }

        // Validate the submitted data
        if ($process->name == $request->name) {
            $this->rules['name'] = 'string|required|min:2|max:64';
        }
        
        $request->validate($this->rules);

        // Save changes
        $process->name = $request->name;
        $process->description = $request->description;
        $process->save();

        return redirect()->route('process.view', $process->id)->with('note', 'Updated.');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Process::whereIn('id', $request->ids)->delete();

            return redirect()->route('processes')->with('note', 'Selected processes have been deleted');
        }
    }

}
