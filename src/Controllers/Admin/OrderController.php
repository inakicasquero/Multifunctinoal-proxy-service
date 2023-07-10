<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Invoice;
use App\Models\Order;
use App\Utils\Tools;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class OrderController extends BaseController
{
    public static array $details = [
        'field' => [
            'op' => '操作',
            'id' => '订单ID',
            'user_id' => '提交用户',
            'product_id' => '商品ID',
            'product_type' => '商品类型',
            'product_name' => '商品名称',
            'coupon' => '优惠码',
            'price' => '金额',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ],
    ];

    public function index(Request $request, Response $response, array $args): ResponseInterface
    {
        return $response->write(
            $this->view()
                ->assign('details', self::$details)
                ->display('admin/order/index.tpl')
        );
    }

    public function detail(Request $request, Response $response, array $args): ResponseInterface
    {
        $id = $args['id'];

        $order = Order::find($id);
        $order->product_type = Tools::getOrderProductType($order);
        $order->status = Tools::getOrderStatus($order);
        $order->create_time = Tools::toDateTime($order->create_time);
        $order->update_time = Tools::toDateTime($order->update_time);

        $product_content = \json_decode($order->product_content, true);

        $invoice = Invoice::where('order_id', $id)->first();
        $invoice->status = Tools::getInvoiceStatus($invoice);
        $invoice->create_time = Tools::toDateTime($invoice->create_time);
        $invoice->update_time = Tools::toDateTime($invoice->update_time);
        $invoice->pay_time = Tools::toDateTime($invoice->pay_time);
        $invoice_content = \json_decode($invoice->content, true);

        return $response->write(
            $this->view()
                ->assign('order', $order)
                ->assign('invoice', $invoice)
                ->assign('product_content', $product_content)
                ->assign('invoice_content', $invoice_content)
                ->display('admin/order/view.tpl')
        );
    }

    public function cancel(Request $request, Response $response, array $args): ResponseInterface
    {
        $order_id = $args['id'];
        $order = Order::find($order_id);

        if ($order->status === 'activated') {
            return $response->withJson([
                'ret' => 0,
                'msg' => '不能取消已激活的产品',
            ]);
        }

        $order->update_time = \time();
        $order->status = 'cancelled';
        $order->save();

        $invoice = Invoice::where('order_id', $order_id)->first();

        if ($invoice === null) {
            return $response->withJson([
                'ret' => 1,
                'msg' => '订单取消成功，但关联账单状态异常',
            ]);
        }

        $invoice->update_time = \time();

        if (\in_array($invoice->status, ['paid_gateway', 'paid_balance', 'paid_admin', 'paid_giftcard'])) {
            $invoice->status = 'cancelled';
            $invoice->save();

            return $response->withJson([
                'ret' => 1,
                'msg' => '订单取消成功，但关联账单已支付',
            ]);
        }

        $invoice->status = 'cancelled';
        $invoice->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '订单取消成功',
        ]);
    }

    public function delete(Request $request, Response $response, array $args): ResponseInterface
    {
        $order_id = $args['id'];
        Order::find($order_id)->delete();
        Invoice::where('order_id', $order_id)->first()->delete();

        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功',
        ]);
    }

    public function ajax(Request $request, Response $response, array $args): ResponseInterface
    {
        $orders = Order::orderBy('id', 'desc')->get();

        foreach ($orders as $order) {
            $order->op = '<button type="button" class="btn btn-red" id="delete-order-' . $order->id . '" 
            onclick="deleteOrder(' . $order->id . ')">删除</button>
            <button type="button" class="btn btn-orange" id="cancel-order-' . $order->id . '" 
            onclick="cancelOrder(' . $order->id . ')">取消</button>
            <a class="btn btn-blue" href="/admin/order/' . $order->id . '/view">查看</a>';
            $order->product_type = Tools::getOrderProductType($order);
            $order->status = Tools::getOrderStatus($order);
            $order->create_time = Tools::toDateTime($order->create_time);
            $order->update_time = Tools::toDateTime($order->update_time);
        }

        return $response->withJson([
            'orders' => $orders,
        ]);
    }
}
