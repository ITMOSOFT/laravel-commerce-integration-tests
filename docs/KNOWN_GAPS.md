# Known Integration Gaps at Harness Bootstrap

These are source-inspection findings; they do not imply individual package unit tests fail.

## Checkout → Orders
CheckoutSession stores `cart_snapshot` and `totals`. Orders `createFromCheckoutSession()` currently reads `items` and top-level subtotal/discount/tax/shipping/grand total attributes. CheckoutSession does not expose those fields or an items relation. The suite characterizes this mismatch and proves that a manually normalized payload can create an order.

## Payments → Orders
Payments can create/capture a payment linked by `order_id`; the inspected implementation does not automatically transition the linked order. The suite records this current boundary.

## Checkout Composer dependency
Checkout README says Cart is required, but inspected Checkout `composer.json` did not declare `itmosoft/laravel-cart`. This harness requires both directly.

## Product variants duplication
Product Catalog owns `catalog_product_variants`; Product Variants owns a separate polymorphic `itmosoft_product_variants` table. A canonical model decision is required.

## Stores migrations
The inspected Stores provider publishes migration stubs instead of auto-loading them. Store integration tests are not included yet.
