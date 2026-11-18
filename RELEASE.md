# Repository Setup and Release Instructions

## Repository
- Owner: `ITMOSOFT`
- Name: `laravel-commerce-integration-tests`
- Recommended visibility: Public
- Description: `Cross-package integration and compatibility tests for the ITMOSOFT Laravel commerce ecosystem.`

Create the empty repository through GitHub's UI. Do not initialize it with extra README/license/.gitignore files.

## Initial push
Run these commands yourself from the extracted project directory. The remote must already be configured.
```bash
git add .
git commit -m "chore: add commerce integration test harness"
git push -u origin main
```

## Validate before tagging
```bash
composer validate --strict
composer update --no-interaction --prefer-dist
composer test
```
Do not tag until local tests pass and GitHub Actions is green.

## Tag and push
```bash
git tag -a v0.1.0 -m "Initial commerce integration test harness"
git push origin v0.1.0
```

## GitHub Release
Title: `v0.1.0 — Initial Commerce Integration Test Harness`

Description:
Initial cross-package integration test harness for the ITMOSOFT Laravel commerce ecosystem.

Includes Testbench for Laravel 12/13, PHP 8.3/8.4 CI, Product Catalog/Pricing/Inventory tests, Cart/Checkout tests, Checkout-to-Orders boundary characterization, Orders-to-Payments boundary characterization, and integration contract documentation.

This release does not certify all ITMOSOFT packages or production database compatibility. MySQL/PostgreSQL jobs and marketplace, B2B, procurement, tenancy and store scenarios remain planned.

Packagist publication is optional and not recommended: this is a test project, not a runtime library.
