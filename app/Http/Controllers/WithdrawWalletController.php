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
use App\Http\Requests\PostWithdrawWallet;

class WithdrawWalletController extends Controller
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
        return view('withdraw-wallet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostWithdrawWallet  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostWithdrawWallet $request)
    {
        $data = $request->only('amount');
        $user = Auth::user();
        $wallet = $user->wallet;
        $balance = $wallet->balance ?? 0;

        if ($balance < $request->amount) {
            return back()->withErrors(['amount' => 'Insufficient Balance'])->withInput();
        }
        DB::beginTransaction();
        try {
            if ($balance >= $request->amount) {
                $category = TransactionCategory::where('name', Constants::TRANSACTION_CATEGORY_WITHDRAWAL)->first();
                $status = TransactionStatus::where('name', Constants::TRANSACTION_STATUS_PENDING)->first();

                $payerBalanceBefore = $wallet->balance;

                $data = array_merge($data, [
                    'transaction_category_id' => $category->id,
                    'transaction_status_id' => $status->id,
                    'payer_balance_before' => $payerBalanceBefore,
                    'payer_id' => $user->id,
                ]);

                $transaction = Transaction::create($data);
                //update the wallet
                // $penceAmount = $wallet->convertToPence($data['amount']);
                $balance =  $balance - $data['amount'];
                $wallet->update(['balance' => $balance]);

                //Update the status 
                $status = TransactionStatus::where('name', Constants::TRANSACTION_STATUS_COMPLETE)->first();
                $payerBalanceAfter = $wallet->balance;
                $data = [
                    'transaction_status_id' => $status->id,
                    'payer_balance_after' => $payerBalanceAfter,
                ];
                $transaction->update($data);

                DB::commit();

                return redirect(route('transactions.index'))->with([
                    'status' => 'Withdrawal Successful'
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
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
