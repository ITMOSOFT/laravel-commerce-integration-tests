# ITMOSOFT Integration Contracts

## Package ownership
Each package owns its tables, models, migrations and lifecycle. Cross-package integration belongs in a host application or optional adapter package. Core packages must not depend on optional workflows. Adapters may depend on both packages they connect.

## Relationships
Use Eloquent APIs, not direct writes to another package's tables. Polymorphic relations must support a host-owned morph map. Morph aliases are persistent data contracts; renames require data migrations. Never assume consumers use ITMOSOFT default model classes.

## Tenant and store context
Tenant and store are distinct. Tenant is an isolation boundary; company is a legal/operating organization; store is a storefront/channel. Each package documents whether models are global, tenant-, company- or store-owned. Test reads, writes, queued jobs and background processing for isolation. Do not add tenant/store columns blindly to global reference data.

## Money
Canonical integration representation: `['amount_minor' => 125099, 'currency' => 'USD']`. Amounts are integer minor units; currency exponent must be currency-aware. Never use binary floating-point for financial calculations. Convert existing decimal storage explicitly. Preserve price, discount, tax, currency and quantity snapshots. FX conversion records rate, source and timestamp. This does not mandate immediate database rewrites.

## Events
Events describe completed business facts and are owned by the package performing the operation. Dispatch important events after the originating transaction commits. Consumers must be idempotent. Critical workflows should use a transactional outbox. Payloads contain stable identifiers and scalar context, not undocumented model internals.

## Lifecycle
Document valid transitions, validation, authorization, transaction boundary, history, after-commit events and idempotency. Payment capture is not order fulfillment or order completion.

## Integration tests
Install real packages through Composer. Exercise public APIs, migrations and real Eloquent relationships. Do not copy package internals into fixtures. Characterize known gaps explicitly; replace them with positive regression tests when fixed. SQLite is fast feedback, not proof of MySQL/PostgreSQL compatibility.
