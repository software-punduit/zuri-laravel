<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\order;
use App\Models\Wallet;
use App\Models\Constants;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\PutOrder;
use App\Http\Requests\PostOrder;
use App\Models\TransactionStatus;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function __construct()
    {
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
        } else {
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
        $restaurants = Restaurant::active()->get();
        $restaurant = $restaurants->first();
        $menuItems = $restaurant->menuItems()->active()->get();
        return view('orders.create', compact('menuItems', 'restaurants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|RedirectResponse
     */
    public function store(PostOrder $request)
    {
        //validate the request
        //Get the data from the request
        //store the data as a new record

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $productIds = $request->product_ids;
            $quantities = $request->product_quantities;
            $products = Menu::whereIn('id', $productIds)->get();
            $restaurant = Restaurant::find($request->restaurant_id);
            $orderData = [
                'user_id' => $user->id,
                'restaurant_id' => $restaurant->id,
                'restaurant_owner_id' => $restaurant->user_id,
                'order_number' => Order::getOrderNumber()
            ];
            $order = Order::create($orderData);
            $orderItems = [];
            $netTotal = 0;

            foreach ($quantities as $key => $quantity) {
                $productId = $productIds[$key];
                $product = $products->first(function ($value) use ($productId) {
                    return $value->id == $productId;
                });
                $subTotal = $quantity * $product->price;
                $netTotal += $subTotal;
                array_push($orderItems, [
                    // 'order_id' => $order->id,
                    'user_id' => $user->id,
                    'menu_id' => $product->id,
                    'restaurant_id' => $product->restaurant_id,
                    'restaurant_owner_id' => $product->restaurant_owner_id,
                    'quantity' => $quantity,
                    'total' => $subTotal,
                ]);
            }
            $order->orderItems()->createMany($orderItems);
            $order->update([
                'sub_total' => $netTotal,
                'net_total' => $netTotal,
            ]);

            $payerWallet = $user->wallet;
            $payerBalanceBefore = $payerWallet->balance ?? 0;
            if ($payerBalanceBefore < $netTotal) {
                DB::rollBack();

                return back()->with(['status' => 'insufficient-balance']);
            }

            $category = TransactionCategory::where('name', Constants::TRANSACTION_CATEGORY_ORDER)->first();
            $status = TransactionStatus::where('name', Constants::TRANSACTION_STATUS_PENDING)->first();

            $payeeWallet = Wallet::where('user_id', $restaurant->user_id)->first();
            $payeeBalanceBefore = $payeeWallet->balance;

            $transactionData = [
                'transaction_category_id' => $category->id,
                'transaction_status_id' => $status->id,
                'payer_balance_before' => $payerBalanceBefore,
                'payer_id' => $user->id,
                'payee_balance_before' => $payeeBalanceBefore,
                'payee_id' => $restaurant->user_id,
                'amount' => $netTotal
            ];

            $transaction = Transaction::create($transactionData);
            //update the wallet
            if ($transaction->payer_id != $transaction->payee_id) {
                $payerBalanceAfter =  $payerBalanceBefore - $transactionData['amount'];
                $payeeBalanceAfter =  $payeeBalanceBefore + $transactionData['amount'];
                $payerWallet->update(['balance' => $payerBalanceAfter]);
                $payeeWallet->update(['balance' => $payeeBalanceAfter]);
            }

            $status = TransactionStatus::where('name', Constants::TRANSACTION_STATUS_COMPLETE)->first();
            $transaction->update(['transaction_status_id' => $status->id]);


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
     * @return \Illuminate\Http\Response|View
     */
    public function show(order $order)
    {
        return view('orders.show', compact('order'));
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
     * @param  \App\Http\Requests\PutOrder  $request
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(PutOrder $request, order $order)
    {

        $data = $request->validated();
        $order->update($data);

        return redirect(route('orders.index'))->with([
            'status' => 'Order Updated Successfully'
        ]);
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
