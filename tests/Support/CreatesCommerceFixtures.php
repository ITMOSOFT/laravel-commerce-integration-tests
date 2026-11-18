<?php
declare(strict_types=1);
namespace ITMOSOFT\CommerceIntegrationTests\Support;
use ItmoSoft\ProductCatalog\Models\Product;
trait CreatesCommerceFixtures {
 protected function createProduct(array $overrides=[]): Product {
  return Product::query()->create(array_merge(['name'=>'Integration Test Product','sku'=>'IT-'.strtoupper(bin2hex(random_bytes(4))),'product_type'=>'simple','status'=>'published','is_active'=>true],$overrides));
 }
}