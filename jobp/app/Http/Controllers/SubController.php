<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\PurchaseMail;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use App\Http\Middleware\IsEmployer;
use App\Http\Middleware\donotAllowUserToMakePayment;
use App\Models\User;

class SubController extends Controller
{
    const WEEKLY_AMOUNT = 20;
    const MONTHLY_AMOUNT = 80;
    const YEARLY_AMOUNT = 200;
    const CURRENCY = 'USD';

    public function __construct()
    {
        $this->middleware(['auth', IsEmployer::class]);
        // $this->middleware(['auth', donotAllowUserToMakePayment::class])->except('subscribe');
    }

    public function subscribe()
    {
        return view('subscription.index');
    }

    public function initiatePayment(Request $request)
    {
        $plans = [
            'weekly' => [
                'name' => 'weekly',
                'description' => 'weekly payment',
                'amount' => self::WEEKLY_AMOUNT,
                'currency' => self::CURRENCY,
                'quantity' => 1,
            ],
            'monthly' => [
                'name' => 'monthly',
                'description' => 'monthly payment',
                'amount' => self::MONTHLY_AMOUNT,
                'currency' => self::CURRENCY,
                'quantity' => 1,
            ],
            'yearly' => [
                'name' => 'yearly',
                'description' => 'yearly payment',
                'amount' => self::YEARLY_AMOUNT,
                'currency' => self::CURRENCY,
                'quantity' => 1,
            ],
        ];

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $selectPlan = null;

            if ($request->is('pay/weekly')) {
                $selectPlan = $plans['weekly'];
                $billingEnds = now()->addWeek()->startOfDay()->toDateString();
            } elseif ($request->is('pay/monthly')) {
                $selectPlan = $plans['monthly'];
                $billingEnds = now()->addMonth()->startOfDay()->toDateString();
            } elseif ($request->is('pay/yearly')) {
                $selectPlan = $plans['yearly'];
                $billingEnds = now()->addYear()->startOfDay()->toDateString();
            }

            if ($selectPlan) {
                $successURl = URL::signedRoute('payment.success', [
                    'plan' => $selectPlan['name'],
                    'billing_ends' => $billingEnds
                ]);

                $product = \Stripe\Product::create([
                    'name' => $selectPlan['name'],
                    'description' => $selectPlan['description'],
                    'type' => 'service',  // 'service' is used for subscription plans
                ]);

                $price = \Stripe\Price::create([
                    'unit_amount' => $selectPlan['amount'] * 100,
                    'currency' => $selectPlan['currency'],
                    'product' => $product->id,
                ]);

                $session = \Stripe\Checkout\Session::create([
                    'line_items' => [[
                        'price' => $price->id,
                        'quantity' => 1,
                    ]],
                    'payment_method_types' => ['card'],
                    'mode' => 'payment',
                    'success_url' => $successURl,
                    'cancel_url' => route('payment.cancel')
                ]);

                // print_r($session);
                // You can redirect the user to the session URL
                return redirect($session->url);
            }
        } catch (\Exception $e) {
            return $e;
            // return response()->json($e);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $plan = $request->plan;
        $billingEnds = $request->billing_ends;
        User::where('id', auth()->user()->id)->update([
            'plan' => $plan,
            'billing_ends' => $billingEnds,
            'status' => 'paid'
        ]);

        try {
            Mail::to(auth()->user())->queue(new PurchaseMail($plan, $billingEnds));
        } catch (\Exception $e) {
            return response()->json($e);
        }
        return redirect()->route('dashboard')->with('success', 'Payment was successfully processed');
    }

    public function cancel()
    {
        return redirect()->route('dashboard')->with('error', 'Payment was unsuccessful!');
    }
}
