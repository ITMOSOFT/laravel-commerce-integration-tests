<?php
declare(strict_types=1);
namespace ITMOSOFT\CommerceIntegrationTests\Integration;
use ITMOSOFT\CommerceIntegrationTests\Support\CreatesCommerceFixtures;
use ITMOSOFT\CommerceIntegrationTests\TestCase;
use ItmoSoft\Orders\Services\OrderService;
use ItmoSoft\Payments\Services\PaymentManager;
final class OrderPaymentBoundaryTest extends TestCase {
 use CreatesCommerceFixtures;
 public function test_payment_can_reference_an_order_and_capture(): void {
  $product=$this->createProduct(); $order=app(OrderService::class)->create(['currency'=>'USD','subtotal'=>'30.00','grand_total'=>'30.00',
   'items'=>[['product_type'=>$product->getMorphClass(),'product_id'=>$product->getKey(),'name'=>$product->name,'sku'=>$product->sku,'quantity'=>1,'unit_price'=>'30.00','line_total'=>'30.00']]]);
  $payments=app(PaymentManager::class); $payment=$payments->create(['order_id'=>$order->getKey(),'amount'=>'30.00','currency'=>'USD','gateway'=>'integration-test','payment_method'=>'card']);
  $captured=$payments->capture($payment,['gateway_reference'=>'integration-capture-001']);
  $this->assertSame('captured',$captured->status); $this->assertSame('30.00',number_format((float)$captured->captured_amount,2,'.',''));
  $this->assertSame($order->getKey(),$captured->order_id);
 }
 /** Characterizes behavior until a PaymentCaptured -> Order transition adapter exists. */
 public function test_payment_capture_does_not_implicitly_change_order_status(): void {
  $product=$this->createProduct(); $order=app(OrderService::class)->create(['currency'=>'USD','subtotal'=>'15.00','grand_total'=>'15.00',
   'items'=>[['product_type'=>$product->getMorphClass(),'product_id'=>$product->getKey(),'name'=>$product->name,'quantity'=>1,'unit_price'=>'15.00','line_total'=>'15.00']]]);
  $payment=app(PaymentManager::class)->create(['order_id'=>$order->getKey(),'amount'=>'15.00','currency'=>'USD','gateway'=>'integration-test']);
  app(PaymentManager::class)->capture($payment); $this->assertSame('pending',$order->fresh()->status);
 }
}