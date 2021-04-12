<?php

namespace App\Http\Controllers;

use App\Jobs\SquareApiJob;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Square\Exceptions\ApiException;
use Illuminate\Pagination\LengthAwarePaginator;

class SquareController extends Controller
{
    public function items(Request $request)
    {
        $search = $request->search;
        $items = square_raw_data( 'items', [] );
        $items = $this->paginate($request, array_filter( $items, function ($item) use ($search) {
            return empty($search) ? true : stripos($item['name'], $search) !== false;
        }));
        $filters = $request->all('search');

        return Inertia::render('Items/Index', compact( 'filters', 'items' ));
    }

    public function orders(Request $request)
    {
        $search = $request->search;
        $orders = square_raw_data('orders', []);
        $orders = $this->paginate($request, array_filter($orders, function ($order) use ($search) {
            return empty($search) ? true : stripos($order['name'], $search) !== false;
        }));
        $filters = $request->all('search');

        return Inertia::render('Orders/Index', compact('filters', 'orders'));
    }

    public function sync()
    {
        SquareApiJob::dispatchNow();

        sleep( 10 );

        return back()->with( 'status', 'Your square data is being synchronized.' );
    }

    private function paginate(Request $request, $items, $per_page = 10)
    {
        $total = count($items); // total count of the set, this is necessary so the paginator will know the total pages to display
        $page = $request->page ?? 1; // get current page from the request, first page is null
        $offset = ($page - 1) * $per_page; // get the offset, how many items need to be "skipped" on this page
        $items = array_slice($items, $offset, $per_page); // the array that we actually pass to the paginator is sliced

        return new LengthAwarePaginator($items, $total, $per_page, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    public function locations()
    {
        $client = square_client();

        try {
            $locationsApi = $client->getLocationsApi();
            $apiResponse = $locationsApi->listLocations();

            if ($apiResponse->isSuccess()) {
                $listLocationsResponse = $apiResponse->getResult();
                $locationsList = $listLocationsResponse->getLocations();
                foreach ($locationsList as $location) {
                    dump($location);
                }
            } else {
                dump($apiResponse->getErrors());
            }
        } catch (ApiException $e) {
            dump("Recieved error while calling Square: " . $e->getMessage());
        }

        exit;

        return Inertia::render('Settings/Index');
    }
}
