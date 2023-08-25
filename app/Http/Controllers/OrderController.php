<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function index()
    {
        $user = Auth::user();
        $orders = collect([]);
        
        if ($user->hasRole(User::RESTUARANT_OWNER)) {
          $orders = $orders->concat($user->restaurantOwnerOrders);
        } elseif ($user->hasRole(User::RESTUARANT_STAFF)) {
            $orders = $orders->concat($user->restaurantStaffOrders);
        }
         else {
            $orders = $orders->concat($user->orders);
        }
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function create()
    {
        $menuItems = Menu::active()->get();
        return view('orders.create', compact('menuItems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostOrder $request)
    {
        //validate the request
        //Get the data from the request
        //store the data as a new record

        DB::beginTransaction();

        try {
            $productIds = $request->product_ids;
            $quantities = $request->product_quantities;
            $products = Menu::whereIn('id', $productIds)->get();
            $orderData = [
                'user_id' => $request->user()->id,
            ];
            $order = Order::create($orderData);
            $orderItems = [];
            $netTotal = 0;

            foreach ($quantities as $key => $quantity) {
                $productId = $productIds[$key];
                $product = $products->first(function($value)use($productId))
                {
                    return $value->id ==$productId;

                });
                $subTotal = $quantity * $product->price;
                $netTotal +=$subTotal;
                array_push($orderItems, [
                    'order_id' => $order->id,
                    'menu_id' => $product->id,
                    'restaurant_id' => $product_restaurant->id,
                    'restaurant_owner_id' => $product->restaurant_owner_id,
                    'quantity' => $quantity,
                    'total' => $subTotal,
                ]);

            }
            OrderItem::createMany($orderItems);
            $order->update([
                'sub_total' => $netTotal,
                'net_total' => $netTotal,
            ]);

            DB::commit();
            return redirect(route('orders.index'))->with([
                'status' => 'Order Created Successfully'
            ]);
        
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(order $order)
    {
        //
    }
}
