<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Media;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render("Backend/Media/List", [
            'records' => $this->records($request),
            'note' => session('note'),
            'stkn' => csrf_token(),
            'usage' => [
                'Order', 'Product', 'Profile'
            ],
        ]);
    }

    private function records(Request $request)
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
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $items = $query->paginate($itemsPerPage, ['*'],'pg', $page);
        return [
            'records' => $items,
            'searchPhrase' => $search,
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

    private function getIndelibleFiles(array $fileIds): array
    {
        $indelibleMediaFiles = [];
        $media = Media::whereIn('id', $fileIds)->get(['id', 'data']);
        if (!empty($media)) {
            $settings = Setting::first();
            $orderFileDelibleStates = json_decode($settings->order_file_delible_states); // Gets the Order Status where media files can be deleted
            foreach ($media as $mediaData) {
                // Use Check the status of orders that can be deleted from settings.
                // If the order cannot be deleted, add to list that should be returned and highlighted.
                if (!empty($mediaData->data)) {
                    $mediaUsageData = json_decode($mediaData->data);
                    if (property_exists($mediaUsageData, 'orders')) {
                        $orders = Order::whereIn('id', $mediaUsageData->orders)->get();
                        if (!empty($orders)) {
                            $indelibleOrders = [];
                            foreach ($orders as $order) {
                                if (!in_array($order->orderStatus->name, $orderFileDelibleStates)) {
                                    array_push($indelibleOrders, [
                                        'id' => $order->id,
                                        'name' => $order->name,
                                        'status' => $order->orderStatus->name,
                                    ]);
                                }
                            }

                            if (!empty($indelibleOrders)) {
                                array_push($indelibleMediaFiles, [
                                    'mediaId' => $mediaData->id,
                                    'indelibleOrders' => $indelibleOrders,
                                ]);
                            }
                        }
                    }

                    // if (property_exists($mediaUsageData, 'products')) {
                    //     # code...
                    // }
                }
            }
        }

        return $indelibleMediaFiles;
    }

    public function delete(Request $request)
    {
        $request->validate([
            'selections' => 'required|array'
        ]);

        // Todo: Before deleting images that are linked to an order, check that the order has been completed and the No-Delete period has been exceeded.
        $indelibleMediaFiles = $this->getIndelibleFiles($request->selections);
        if (!empty($indelibleMediaFiles)) {
            return response()->json([
                'message' => 'The highlighted files cannot be deleted because they are still in use. To review the order statuses where file deletion is permitted, go to Settings > File Upload > Order Files / Space Management.',
                'mediaData' => $indelibleMediaFiles,
            ], 422);
        }

        Media::deleteMedia($request->selections);

        return [
            'status' => 'success',
            'message' => 'Selected media files have been deleted'
        ];
    }

    public function getMediaUsage(Request $request)
    {
        $orders = [];
        $products = [];

        if(isset($request->data['orders']) && is_array($request->data['orders'])){
            $orders = Order::whereIn('id', $request->data['orders'])->get(['id', 'name']);
        }

        if(isset($request->data['products']) && is_array($request->data['products'])){
            $products = Item::whereIn('id', $request->data['products'])->get(['id', 'name']);
        }

        return [
            'status' => 'success',
            'orders' => $orders,
            'products' => $products
        ];
    }
}
