<?php

declare(strict_types=1);

namespace App\Services\Gateway;

use App\Models\Paylist;
use App\Models\Setting;
use App\Services\Auth;
use App\Services\View;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

final class StripeCard extends AbstractPayment
{
    public static function _name(): string
    {
        return 'stripe_card';
    }

    public static function _enable(): bool
    {
        if (self::getActiveGateway('stripe_card') && Setting::obtain('stripe_card')) {
            return true;
        }

        return false;
    }

    public static function _readableName(): string
    {
        return 'Stripe';
    }

    public function purchase(Request $request, Response $response, array $args): ResponseInterface
    {
        $trade_no = uniqid();
        $user = Auth::getUser();
        $configs = Setting::getClass('stripe');
        $price = $request->getParam('price');

        $pl = new Paylist();
        $pl->userid = $user->id;
        $pl->total = $price;
        $pl->tradeno = $trade_no;
        $pl->save();

        $params = [
            'trade_no' => $trade_no,
            'sign' => md5($trade_no . ':' . $configs['stripe_webhook_key']),
        ];

        $exchange_amount = $price / self::exchange($configs['stripe_currency']) * 100;

        \Stripe\Stripe::setApiKey($configs['stripe_sk']);
        $session = \Stripe\Checkout\Session::create([
            'customer_email' => $user->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => $configs['stripe_currency'],
                    'product_data' => [
                        'name' => 'Account Recharge',
                    ],
                    'unit_amount' => (int) $exchange_amount,
                ],
                'quantity' => 1,
            ],
            ],
            'mode' => 'payment',
            'success_url' => self::getUserReturnUrl() . '?session_id={CHECKOUT_SESSION_ID}&' . http_build_query($params),
            'cancel_url' => $_ENV['baseUrl'] . '/user/code',
        ]);

        return $response->withRedirect($session->url);
    }

    public function notify($request, $response, $args): ResponseInterface
    {
        return $response->write('ok');
    }

    public static function getPurchaseHTML(): string
    {
        return View::getSmarty()->fetch('gateway/stripe_card.tpl');
    }

    public function getReturnHTML($request, $response, $args): ResponseInterface
    {
        $sign = $request->getParam('sign');
        $trade_no = $request->getParam('trade_no');
        $session_id = $request->getParam('session_id');

        $_sign = md5($trade_no . ':' . Setting::obtain('stripe_webhook_key'));
        if ($_sign !== $sign) {
            die('error_sign');
        }

        $stripe = new \Stripe\StripeClient(Setting::obtain('stripe_sk'));
        $session = $stripe->checkout->sessions->retrieve($session_id, []);

        if ($session->payment_status === 'paid') {
            $this->postPayment($trade_no, '银行卡支付');
        }

        return $response->withRedirect($_ENV['baseUrl'] . '/user/code');
    }

    public static function exchange($currency)
    {
        $ch = curl_init();
        $url = 'https://api.exchangerate.host/latest?symbols=CNY&base=' . strtoupper($currency);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $currency = \json_decode(curl_exec($ch));
        curl_close($ch);

        return $currency->rates->CNY;
    }
}
