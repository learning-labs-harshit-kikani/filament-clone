# ADR-001: Package identity, support baseline, and boundaries

- **Status:** Accepted
- **Date:** 2026-09-02
- **Decision owners:** Project maintainers

## Context

The repository starts without package scaffolding. Before `composer.json`, a
service provider, or public classes are created, we need to fix the package
identity and boundaries that determine its public API, supported applications,
test strategy, and module layout.

This is an independent educational implementation of public, documented
admin-panel behavior. It must not claim official affiliation, reuse reference
branding/assets, or copy source code.

## Decision

### Identity

Use the following initial package identity:

| Concern | Decision |
|---|---|
| Composer package | `learning-labs/filament-clone` |
| PHP root namespace | `LearningLabs\\FilamentClone` |
| Display name | Learning Labs Admin Panel |
| License | MIT, subject to confirmation before the first public release |
| Stability | `0.x`; public APIs are documented but may evolve with migration notes |

The project will call itself an independent Laravel/Livewire admin-panel package
in user-facing documentation. It will not present itself as the reference
project or use its logo/brand identity.

### Compatibility baseline

| Dependency | Supported constraint | Rationale |
|---|---|---|
| PHP | `^8.3` | Matches Laravel 13’s baseline while remaining compatible with Laravel 12. |
| Laravel framework/contracts | `^12.0 || ^13.0` | Laravel 11 security support has ended; begin with currently supported releases. |
| Livewire | `^4.0` | Use the current Livewire generation for server-driven page/component behavior. |
| Testbench (development) | `^10.0 || ^11.0` | Match the Laravel 12/13 test environments. |
| CSS/build tooling | Vite-compatible, package-local source assets | Keeps frontend integration conventional and application-controlled. |

These are initial constraints. The Day 3 Composer scaffold must lock exact
development versions and verify the intersection with a real test matrix before
declaring support.

### Required runtime dependencies

The first package version should depend only on the Laravel framework and
Livewire. It must not add a UI kit, third-party admin library, JavaScript
framework, icon set, permissions package, or query helper until a concrete
feature proves that Laravel/Livewire cannot provide the need.

### Package module boundaries

```text
src/
├── Actions/          Reusable action configuration and execution
├── Auth/             Panel-facing authentication adapters and pages
├── Commands/         Artisan install/generator/asset commands
├── Components/       Shared Blade and Livewire-facing UI primitives
├── Concerns/         Small, focused reusable behavior only
├── Contracts/        Stable extension contracts
├── Forms/            Form consumer of shared schema infrastructure
├── Infolists/        Read-only schema consumer
├── Livewire/         Shared Livewire integration utilities
├── Navigation/       Panel/resource/page navigation value objects
├── Notifications/    Notification configuration and delivery
├── Panels/           Panel configuration, discovery, registry, routes
├── Resources/        Eloquent resource metadata and CRUD pages
├── Schemas/          Generic declarative component/state infrastructure
├── Support/          Narrow framework-neutral support utilities
├── Tables/           Eloquent query pipeline, columns, filters, state
└── Widgets/          Dashboard/page widget base classes and registry

config/                Package defaults only
resources/views/       Package Blade views
resources/css/         Original token-driven styles
resources/js/          Small browser-only behaviors
routes/                Package route registration entry points
tests/                  Unit, Feature, and Livewire integration coverage
stubs/                  Generator templates
docs/                   User documentation and architecture decisions
```

### Dependency direction

```text
Laravel / Livewire
        ↓
Support + Contracts
        ↓
Panels + Schemas
        ↓
Forms / Infolists / Tables
        ↓
Actions
        ↓
Resources
        ↓
Widgets / Pages / Navigation / Plugins
```

`Schemas` may not depend on `Resources` or Eloquent. `Tables` own only the
composition of an Eloquent query; resource pages own CRUD lifecycle and
authorization. Blade views render prepared state and do not execute business
queries.

### Service-provider strategy

Create one package service provider in Day 4. Its `register()` method will bind
configuration and container services; its `boot()` method will load/publish
views, translations, routes, migrations, assets, and Livewire components. Major
registries will be container-bound objects, never mutable global static arrays.

### State and security boundaries

- Package defaults belong in config; panel configuration belongs on a `Panel`
  instance; user/session/Livewire state remains request-scoped.
- Every record mutation and bulk action must re-authorize on the server.
- Dynamic table queries use Eloquent/query-builder parameter binding only.
- File-upload support is deferred until it can enforce MIME, size, storage, and
  authorization policy.
- The package never logs credentials, session data, API tokens, or sensitive
  model fields.

## Alternatives considered

### Support Laravel 11 as well

Rejected for the initial baseline because its security support has ended. A
future release may add it only with a tested dependency matrix and an explicit
reason to support legacy applications.

### Use a static global facade as the primary registry

Rejected because panel/resource collections would leak between requests and
tests, and multiple panels would be difficult to isolate. Container-bound
registries provide explicit lifecycle and testability.

### Build resources first

Rejected because resources depend on stable schemas, forms, tables, actions,
routes, and authorization. The plan’s inside-out sequence reduces duplicated
or throwaway abstractions.

### Add a full client-side SPA framework

Rejected. Livewire plus small browser behaviors is sufficient for the initial
server-driven panel. A dedicated SPA framework would increase package coupling
without resolving a current requirement.

## Consequences

- Day 3 will create Composer metadata and a Testbench harness for Laravel 12
  and 13 rather than implementing panel UI.
- PHP 8.2 and Laravel 11 applications are intentionally unsupported initially.
- Composer availability is required before any package skeleton can be
  installed, autoloaded, or tested.
- Public namespace/package identity should be reconsidered before external
  publication, because renaming after adoption is a breaking change.

## Evidence

- [Laravel 12 release notes](https://laravel.com/framework/docs/12.x/releases)
  list Laravel support windows and PHP compatibility.
- [Livewire 4 installation](https://livewire.laravel.com/docs/4.x/installation)
  documents Laravel 10+ and PHP 8.1+ baseline requirements.
- [Orchestra Testbench compatibility](https://packagist.org/packages/orchestra/testbench-core)
  maps Testbench 10 to Laravel 12 and Testbench 11 to Laravel 13.
