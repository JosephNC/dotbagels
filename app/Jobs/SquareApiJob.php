<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Square\Exceptions\ApiException;
use Square\Models\CatalogObject;
use Square\Models\CatalogObjectType;
use Square\Models\Money;
use Square\Models\Order;
use Square\Models\OrderLineItem;
use Square\Models\SearchOrdersFilter;
use Square\Models\SearchOrdersFulfillmentFilter;
use Square\Models\SearchOrdersQuery;
use Square\Models\SearchOrdersRequest;
use Square\Models\SearchOrdersSort;
use Square\Models\SearchOrdersStateFilter;

class SquareApiJob implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Instance of Square client
     * 
     * @return SquareClient
     */
    private $client;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = square_client();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $items = $this->loadItems();
        $orders = $this->loadOrders();

        if ( $items !== false ) square_raw_data( 'items', $items, true );
        if ( $orders !== false ) square_raw_data( 'orders', $orders, true );

        return true;
    }

    private function loadItems()
    {
        try {
            $data = [];

            $api_response = $this->client->getCatalogApi()->listCatalog(null, CatalogObjectType::ITEM);

            if ($api_response->isError()) throw new \Exception($api_response->getErrors()[0]->getDetail());

            $result = $api_response->getResult();

            $data = array_map(function (CatalogObject $item) {
                $item_data = $item->getItemData();
                $variations = $item_data->getVariations();
                $prices = (array) array_map(function (CatalogObject $var) {

                    $variation_data     = $var->getItemVariationData();
                    // $name               = $variation_data->getName();
                    $money              = $variation_data->getPriceMoney();

                    if (!$money instanceof Money) return 0;

                    $amount             = (float) $money->getAmount() / 100;
                    // $currency           = $money->getCurrency();
                    // $price              = money_form($amount, $currency, true);

                    return $amount;

                    // return compact('name', 'amount', 'currency', 'price');
                }, $variations);

                if (count($prices) > 1) {
                    $min = money_form(min($prices), null, true);
                    $max = money_form(max($prices), null, true);

                    $price = "$min - $max";
                } else {
                    $price = money_form($prices[0], null, true);
                }

                return [
                    'id' => $item->getId(),
                    'name' => $item_data->getName(),
                    'price' => $price,
                ];
            }, (array) $result->getObjects());

            return $data;
        } catch (ApiException $e) {
            logger("Recieved error while calling Square: " . $e->getMessage());
        } catch (\Throwable $e) {
            logger("Error occured: " . $e->getMessage());
        }

        return false;
    }

    private function loadOrders()
    {
        try {
            $data = [];
            $location_id = settings('square_location_id');
            $state_filter = new SearchOrdersStateFilter(['OPEN', 'COMPLETED', 'CANCELED']);
            $fulfilment_filter = new SearchOrdersFulfillmentFilter();
            $fulfilment_filter->setFulfillmentStates(['PROPOSED', 'RESERVED', 'PREPARED', 'COMPLETED', 'CANCELED', 'FAILED']);

            $filter = new SearchOrdersFilter();
            $filter->setStateFilter($state_filter);
            // $filter->setFulfillmentFilter( $fulfilment_filter );

            $sort = new SearchOrdersSort('CREATED_AT');
            $sort->setSortOrder('DESC');

            $query = new SearchOrdersQuery();
            $query->setFilter($filter);
            $query->setSort($sort);

            $body = new SearchOrdersRequest();
            $body->setLocationIds([$location_id]);
            $body->setQuery($query);
            $body->setReturnEntries(false);

            $api_response = $this->client->getOrdersApi()->searchOrders($body);

            if ($api_response->isError()) throw new \Exception($api_response->getErrors()[0]->getDetail());

            $result = $api_response->getResult();

            $data = array_map(function (Order $item) {
                $name = (array) array_map(function (OrderLineItem $var) {
                    $name = $var->getName();
                    $qty = (int) $var->getQuantity();

                    return "$name (x$qty)";
                }, (array) $item->getLineItems() );

                $money = $item->getTotalMoney();

                $price = money_form( !$money instanceof Money ? 0 : (float) $money->getAmount() / 100, null, true );

                return [
                    'id'    => $item->getId(),
                    'state' => $item->getState(),
                    'name'  => join( ', ', $name ),
                    'price' => $price,
                ];
            }, (array) $result->getOrders());

            return $data;
        } catch (ApiException $e) {
            logger("Recieved error while calling Square: " . $e->getMessage());
        } catch (\Throwable $e) {
            logger("Error occured: " . $e->getMessage());
        }

        return false;
    }

}
