<?php
declare(strict_types=1);
namespace ITMOSOFT\CommerceIntegrationTests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ItmoSoft\Cart\CartServiceProvider;
use ItmoSoft\Checkout\CheckoutServiceProvider;
use ItmoSoft\Inventory\InventoryServiceProvider;
use ItmoSoft\Orders\OrdersServiceProvider;
use ItmoSoft\Payments\PaymentsServiceProvider;
use ItmoSoft\Pricing\PricingServiceProvider;
use ItmoSoft\ProductCatalog\ProductCatalogServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
abstract class TestCase extends Orchestra
{
    use RefreshDatabase;
 protected function getPackageProviders($app): array {
  return [ProductCatalogServiceProvider::class,PricingServiceProvider::class,InventoryServiceProvider::class,CartServiceProvider::class,CheckoutServiceProvider::class,OrdersServiceProvider::class,PaymentsServiceProvider::class];
 }
 protected function getEnvironmentSetUp($app): void {
  $app['config']->set('database.default','testing');
  $app['config']->set('database.connections.testing',['driver'=>'sqlite','database'=>':memory:','prefix'=>'','foreign_key_constraints'=>true]);
  $app['config']->set('cache.default','array'); $app['config']->set('queue.default','sync'); $app['config']->set('session.driver','array');
 }
}