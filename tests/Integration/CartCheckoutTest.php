<?php
declare(strict_types=1);
namespace ITMOSOFT\CommerceIntegrationTests\Integration;
use ITMOSOFT\CommerceIntegrationTests\Support\CreatesCommerceFixtures;
use ITMOSOFT\CommerceIntegrationTests\TestCase;
use ItmoSoft\Cart\Services\CartManager;
use ItmoSoft\Checkout\Services\CheckoutManager;
final class CartCheckoutTest extends TestCase {
 use CreatesCommerceFixtures;
 public function test_cart_snapshot_flows_into_checkout_and_checkout_completes(): void {
  $product=$this->createProduct(); $cart=app(CartManager::class)->create(sessionId:'integration-session-001',currency:'USD');
  app(CartManager::class)->add($cart,$product,2,'25.50',['size'=>'M'],['source'=>'integration-test']);
  $manager=app(CheckoutManager::class); $checkout=$manager->start($cart);
  $this->assertCount(1,$checkout->cart_snapshot); $this->assertSame('25.50',(string)$checkout->cart_snapshot[0]['unit_price']);
  $address=['name'=>'Integration Customer','address_line_1'=>'1 Test Street','city'=>'Multan','country'=>'PK'];
  $manager->setAddresses($checkout,$address,$address); $manager->selectShipping($checkout,'integration-standard','5.00');
  $manager->setTotals($checkout,discount:'2.00',tax:'1.00'); $this->assertSame([],$manager->validate($checkout));
  $completed=$manager->complete($checkout->fresh()); $this->assertSame('completed',$completed->status);
  $this->assertSame('55.00',(string)$completed->totals['grand_total']);
 }
}