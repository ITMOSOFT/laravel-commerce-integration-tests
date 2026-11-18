<?php
declare(strict_types=1);
namespace ITMOSOFT\CommerceIntegrationTests\Integration;
use InvalidArgumentException;
use ITMOSOFT\CommerceIntegrationTests\Support\CreatesCommerceFixtures;
use ITMOSOFT\CommerceIntegrationTests\TestCase;
use ItmoSoft\Cart\Services\CartManager;
use ItmoSoft\Checkout\Services\CheckoutManager;
use ItmoSoft\Orders\Services\OrderService;
final class CheckoutOrderBoundaryTest extends TestCase {
 use CreatesCommerceFixtures;
 /** Characterizes the current schema mismatch. Replace with a successful adapter regression test when fixed. */
 public function test_checkout_session_is_not_yet_compatible_with_orders_session_adapter(): void {
  $product=$this->createProduct(); $cart=app(CartManager::class)->create(sessionId:'known-gap-session',currency:'USD');
  app(CartManager::class)->add($cart,$product,1,'10.00'); $checkout=app(CheckoutManager::class)->start($cart);
  $this->assertArrayHasKey('cart_snapshot',$checkout->getAttributes()); $this->assertArrayHasKey('totals',$checkout->getAttributes());
  $this->expectException(InvalidArgumentException::class); $this->expectExceptionMessage('An order must contain at least one item.');
  app(OrderService::class)->createFromCheckoutSession($checkout);
 }
 public function test_normalized_checkout_snapshot_can_create_an_order(): void {
  $product=$this->createProduct(); $cart=app(CartManager::class)->create(sessionId:'normalized-session',currency:'USD');
  app(CartManager::class)->add($cart,$product,2,'10.00'); $checkout=app(CheckoutManager::class)->start($cart); $line=$checkout->cart_snapshot[0];
  $order=app(OrderService::class)->create([
   'currency'=>$checkout->currency,'subtotal'=>$checkout->totals['subtotal'],'discount_total'=>$checkout->totals['discount'],
   'tax_total'=>$checkout->totals['tax'],'shipping_total'=>$checkout->totals['shipping'],'grand_total'=>$checkout->totals['grand_total'],
   'items'=>[['product_type'=>$line['itemable_type'],'product_id'=>$line['itemable_id'],'name'=>$line['name'],'sku'=>$line['sku'],
   'quantity'=>$line['quantity'],'unit_price'=>$line['unit_price'],'line_total'=>$line['line_total'],'product_snapshot'=>$line]],
  ]);
  $this->assertCount(1,$order->items); $this->assertSame($product->getKey(),$order->items->first()->product_id);
  $this->assertSame('20.00',number_format((float)$order->grand_total,2,'.',''));
 }
}