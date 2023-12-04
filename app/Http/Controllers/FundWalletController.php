<?php

namespace App\Http\Controllers;

use App\Models\Constants;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionStatus;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostFundWallet;

class FundWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|View
     */
    public function create()
    {

        return view('fund-wallet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostFundWallet  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostFundWallet $request)
    {
        DB::beginTransaction();

        try {

            $user = Auth::user();
            $data = $request->only('amount');
            $category = TransactionCategory::where('name', Constants::TRANSACTION_CATEGORY_FUND_WALLET)->first();
            $status = TransactionStatus::where('name', Constants::TRANSACTION_STATUS_PENDING)->first();
            $wallet = $user->wallet;
            $payeeBalanceBefore = $wallet->balance;

            $data = array_merge($data, [
                'transaction_category_id' => $category->id,
                'transaction_status_id' => $status->id,
                'payee_balance_before' => $payeeBalanceBefore,
                'payee_id' => $user->id,
            ]);

            $transaction = Transaction::create($data);
            //update the wallet
            $penceAmount = $wallet->convertToPence($data['amount']);
            $wallet->increment('balance', $penceAmount);

            //Update the status 
            $status = TransactionStatus::where('name', Constants::TRANSACTION_STATUS_COMPLETE)->first();
            $payeeBalanceAfter = $wallet->balance;
            $data = [
                'transaction_status_id' => $status->id,
                'payee_balance_after' => $payeeBalanceAfter,
            ];
            $transaction->update($data);

            DB::commit();

            return redirect(route('transactions.index'))->with([
                'status' => 'Wallet Funded Successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
