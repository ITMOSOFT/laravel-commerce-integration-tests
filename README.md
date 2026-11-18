# ITMOSOFT Laravel Commerce Integration Tests

Cross-package integration tests for the ITMOSOFT Laravel commerce ecosystem. This is a test harness, not a runtime commerce package.

## Initial coverage
1. Product Catalog → Pricing → Inventory.
2. Cart → Checkout.
3. Checkout snapshot → Orders boundary.
4. Orders → Payments reference and capture boundary.

## Package sources
Composer installs public VCS repositories: `laravel-product-catalog`, `laravel-pricing`, `laravel-inventory`, `laravel-cart`, `laravel-checkout`, `laravel-orders`, and `laravel-payments`, all under the ITMOSOFT GitHub organization.

`dev-main` constraints are intentional before every package has a Packagist release. Prefer tagged constraints once package releases and dependency constraints are stable.

## Requirements
PHP 8.3/8.4, Composer 2, SQLite with PDO SQLite, and access to the public ITMOSOFT repositories.

## Install and run
```bash
composer validate --strict
composer update --no-interaction --prefer-dist
composer test
```
Run only integration tests: `composer test:integration`.

Testbench uses SQLite `:memory:` and loads package migrations from registered providers. SQLite does not prove production row-lock behavior or MySQL/PostgreSQL compatibility. Add dedicated MySQL 8 and PostgreSQL CI jobs before claiming database compatibility.

## Known gaps
Characterization tests document that Checkout's `cart_snapshot`/`totals` shape is not directly compatible with Orders' `items`/top-level totals adapter, and Payment capture does not automatically transition the linked Order. See `docs/KNOWN_GAPS.md` and `docs/INTEGRATION_CONTRACTS.md`.

## Repository details
- GitHub: `ITMOSOFT/laravel-commerce-integration-tests`
- Suggested description: `Cross-package integration and compatibility tests for the ITMOSOFT Laravel commerce ecosystem.`
- Packagist publication is optional and not recommended initially; this is a test project, not a runtime library.

## Validation status
Static project files are included. PHPUnit has not been executed in the generation environment. A successful local and CI run is required before treating this harness as validated.
