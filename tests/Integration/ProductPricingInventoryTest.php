<?php
declare(strict_types=1);
namespace ITMOSOFT\CommerceIntegrationTests\Integration;
use ITMOSOFT\CommerceIntegrationTests\Support\CreatesCommerceFixtures;
use ITMOSOFT\CommerceIntegrationTests\TestCase;
use ItmoSoft\Inventory\Models\StockLocation;
use ItmoSoft\Inventory\Services\InventoryManager;
use ItmoSoft\Pricing\Models\Price;
use ItmoSoft\Pricing\Services\PriceResolver;
final class ProductPricingInventoryTest extends TestCase {
 use CreatesCommerceFixtures;
 public function test_catalog_product_can_be_priced_and_inventoried(): void {
  $product=$this->createProduct();
  Price::query()->create(['priceable_type'=>$product->getMorphClass(),'priceable_id'=>$product->getKey(),'currency'=>'USD','amount'=>'42.50','min_quantity'=>1,'is_active'=>true,'priority'=>10,'context'=>[]]);
  $price=app(PriceResolver::class)->resolve($product,'USD',1);
  $this->assertNotNull($price); $this->assertSame('42.50',number_format((float)$price->amount,2,'.',''));
  $location=StockLocation::query()->create(['name'=>'Integration Warehouse','code'=>'INT-'.strtoupper(bin2hex(random_bytes(3)))]);
  $inventory=app(InventoryManager::class); $inventory->receive($product,$location,12,'INTEGRATION-RECEIPT');
  $reservation=$inventory->reserve($product,$location,3,'INTEGRATION-ORDER'); $stock=$inventory->stockFor($product,$location);
  $this->assertSame(12,(int)$stock->quantity_on_hand); $this->assertSame(3,(int)$stock->quantity_reserved); $this->assertSame(9,(int)$stock->available_quantity);
  $inventory->release($reservation); $this->assertSame(0,(int)$inventory->stockFor($product,$location)->quantity_reserved);
 }
}