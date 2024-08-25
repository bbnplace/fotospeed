<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function index()
    {
        return Inertia::render("Backend/Media/List", [
            'endpoint' => route('media.records'),
            'note' => session('note'),
            'stkn' => csrf_token(),
            'usage' => [
                'Order', 'Product', 'Profile'
            ],
        ]);
    }

    public function records(Request $request)
    {
        $items = [];
        $itemsCount = 0;

        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Media::query();
        $query->with(['customer' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['staff' => function ($query) {
            $query->select('id', 'name');
        }]);

        if (!empty($search)) {
            $searchTerm = $search;
            if (!empty($searchTerm)) {
               $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('height', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('width', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('weight', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('print_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('cover_print_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('sheet_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $itemsCount = $query->count();
        $items = $query->take($itemsPerPage)
            ->skip($itemsPerPage * ($page - 1))
            ->get();

        return [
            'records' => $items,
            'totalRecords' => $itemsCount,
        ];
    }

    public function add()
    {

    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=> 'nullable|string|max:64|unique:media,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'usage' => ['required', Rule::in(['Order', 'Product', 'Profile'])]
        ]);

        $media = Media::findOrFail($id);
        $media->name = $request->name;
        $media->description = $request->description;
        $media->usage = strtolower($request->usage);

        if (empty($media->thumbnail_100)) {
            $srcFile = Storage::get($media->path);
            $previewImgSize = 100;
            $previewImageFile = str_replace("images/","images/thumbnails/100/", $media->path);
            Media::createThumbnail($srcFile, $previewImageFile, $previewImgSize);
            $previewImgPath = sprintf('%s/%s', env('APP_URL'), $previewImageFile);
            $media->thumbnail_100 = $previewImgPath;
        }

        $media->save();

        return [
            'status' => 'success',
            'response' => 'Saved'
        ];
    }

    public function view($id)
    {

    }

    public function delete(Request $request)
    {

    }
}
