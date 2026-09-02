# Compatibility Matrix

## Purpose and scope

This matrix records the public developer-facing behavior that this independent,
learning-oriented package intends to cover. Filament 5 public documentation is a
behavioral reference only. This project will use its own implementation, visual
identity, assets, documentation, and internal architecture.

**Status legend:**

- `Planned` — recorded, not implemented.
- `In progress` — implementation has started and is not yet verified.
- `Verified` — behavior, tests, and documentation are complete.
- `Deferred` — deliberately outside the current milestone.

Current project status: package scaffolding has not started; every feature below
is `Planned` unless noted otherwise.

## Compatibility principles

- Preserve Laravel-native concepts: service providers, the service container,
  routing, Eloquent, validation, policies, Blade, and Livewire.
- Prefer compatible developer ergonomics where that is useful, but do not copy
  implementation code or branding.
- Keep configuration, runtime state, and model state separate.
- Treat authorization, validation, query safety, and test coverage as acceptance
  requirements—not optional polish.
- Build the dependency chain from panels and shared schema infrastructure toward
  CRUD resources, never in reverse.

## Feature inventory

| Area | Reference behavior to support | Planned public surface | Status | Verification target |
|---|---|---|---|---|
| Package | Installable Laravel package with publishable configuration, views, assets, translations, migrations, routes, and Livewire registrations. | `FilamentCloneServiceProvider`; config file; publish tags. | In progress | Testbench provider/resource-registration tests pass; fresh-app install remains for the release gate. |
| Panels | Independently configured panels with an ID, path, domain, middleware, auth, resources, pages, widgets, theme, navigation, and lifecycle hooks. | `Panel::make()->id()->path()->middleware()->authMiddleware()->resources()->pages()->widgets()->brandName()->bootUsing()`. | Planned | Multi-panel route and isolation tests. |
| Panel providers | A panel is configured through an application service provider. | `Contracts\\PanelProvider::panel(Panel $panel): Panel`. | Planned | Provider discovery/registration tests. |
| Panel routing | Panel login, logout, dashboard, resource and custom-page routes resolve within the current panel; URLs can be generated for a panel. | `PanelManager`, `PanelRegistry`, `PanelResolver`; panel URL helpers. | Planned | Named-route and cross-panel URL tests. |
| Authentication | A panel can expose login/logout and integrate with Laravel session/auth guards. | Panel auth configuration plus auth Livewire/pages. | Planned | Guest redirect, login, logout, and session-regeneration tests. |
| Schemas | Server-driven, declarative component trees with nesting, lifecycle, visibility, disabled state, state paths, and rendering. | `Schemas\\Schema`, `Component`, `ComponentContainer`, `ComponentRegistry`. | Planned | Rendering, nesting, state-path, and conditional tests. |
| Schema utilities | Closures can receive controlled contextual values and container dependencies. | `Get`, `Set`, record, operation, component, Livewire injection. | Planned | Injection ordering and container-resolution tests. |
| Layouts | Layout components arrange child schema components, including sections, grids, tabs, wizards, fieldsets, stacks, splits, callouts, and empty states. | Layout component classes with fluent configuration. | Planned | Responsive/rendering and nested-child tests. |
| Forms | Declarative, model-bound forms fill, validate, hydrate, dehydrate, reset, and return state. | `Forms\\Form` and shared field contract. | Planned | Model binding, validation, state lifecycle, and reactive tests. |
| Form fields | Common input types include text, textarea, hidden, checkbox, toggle, radio, select, date/time, file, repeatable, builder, tags, key-value, color, slider, code and rich-text/Markdown fields. | `Forms\\Components\\*` fluent fields. | Planned | Field-level validation/render/state tests. |
| Relationships | Form controls can select and search Eloquent relationships and support multiple values where applicable. | Relationship-aware form concerns/fields. | Planned | Authorization, constrained-query, and relationship-persistence tests. |
| Infolists | Read-only schemas display text, icon, image, color, code, key-value, repeatable, and custom entries. | `Infolists\\Infolist`, `Entries\\*`. | Planned | Record/data-source and rendering tests. |
| Tables | Eloquent-backed data tables support composable columns, filters, actions, sorting, search, pagination, selection, summaries, groups, and empty/loading states. | `Tables\\Table`, columns, filters, table state/query pipeline. | Planned | Database query, pagination, empty-state, and performance tests. |
| Table columns | Text, icon, image, badge, boolean, date/time, color, select, toggle, relationship, custom, and summary behavior. | `Tables\\Columns\\*` fluent columns. | Planned | Formatting, sort, search, and visibility tests. |
| Table filters | Select, ternary, text, date/range, relationship, and custom filters can constrain database queries. | `Tables\\Filters\\*`; parameterized query callbacks. | Planned | Filter persistence, SQL-safety, and authorization tests. |
| Table actions | Record, header, bulk, and grouped actions can be visible/disabled/authorized and execute against trusted server state. | Table action adapters over shared action engine. | Planned | Selection, action authorization, and bulk-operation tests. |
| Resources | Static resource definitions map Eloquent models to form, table, infolist, navigation, pages, and relations. | `Resources\\Resource`, `ResourceRegistry`; `form()`, `table()`, `getPages()`, `getUrl()`. | Planned | Resource discovery, URLs, and model-query tests. |
| Resource pages | Generated CRUD pages list, create, edit, optionally view, delete/restore/force-delete, and manage relationships. Pages are full-page Livewire components. | `ListRecords`, `CreateRecord`, `EditRecord`, `ViewRecord`, relation page bases. | Planned | CRUD lifecycle, missing-record, policy, hook, and redirect tests. |
| Actions | Reusable actions execute callbacks, inject context, enforce authorization, and can render confirmation or data-entry modals/slide-overs. | `Actions\\Action`, `ActionGroup`, `ActionManager`; built-ins for CRUD, replicate, import/export. | Planned | Modal state, confirmation, injection, and failure tests. |
| Notifications | Transient UI notifications expose title, body, icon, severity, duration, position, and actions; database/broadcast delivery is later scope. | `Notifications\\Notification::make()->title()->body()->success()->send()`. | Planned | Session/Livewire/browser event delivery tests. |
| Widgets | Dashboard and page widgets may be stats, charts, tables, or custom Livewire components; they can be sorted, sized, filtered, and conditionally visible. | `Widgets\\Widget`, `StatsOverviewWidget`, `ChartWidget`, `TableWidget`. | Planned | Registration, visibility, filter, and render tests. |
| Custom pages | Custom, routable panel pages are full-page Livewire components with navigation, actions, schemas, widgets, and authorization. | `Pages\\Page` base and panel page registry. | Planned | Route, guard, navigation, and action tests. |
| Navigation | Sidebar/top navigation supports items, groups, sections/clusters, icons, badges, sort order, active state, visibility, and authorization. | `Navigation\\NavigationItem`, groups/sections, panel configuration. | Planned | Active-state and authorization visibility tests. |
| User features | User menu, avatar/display name, profile, password reset, email verification, MFA architecture, and tenant-aware entry points. | Auth/user contracts and panel configuration. | Planned | Security-focused feature tests. |
| Styling | Original design tokens, colors, icons, themes, dark mode, CSS hooks, responsive layout, and accessible components. | Panel theme/color configuration and Blade components. | Planned | Render snapshots plus keyboard/ARIA checks. |
| Assets | Per-panel CSS/JS assets can be registered, published, versioned, and built with Vite-compatible tooling. | Asset registry and publish/build commands. | Planned | Asset URL and publish tests. |
| Render hooks | Intentional extension points can inject content at supported panel/page regions. | Render-hook registry and named hook constants. | Planned | Hook ordering and isolation tests. |
| Plugins | Panel and standalone plugins register resources, pages, widgets, navigation, assets, and configuration through a stable contract. | `Contracts\\Plugin`, `PluginManager`. | Planned | Plugin registration and panel-isolation tests. |
| Testing | Public facilities are testable through unit, integration, feature, Livewire, and optional browser tests. | Test helpers only after core behavior is stable. | Planned | Package Testbench suite and documented examples. |
| Deployment/upgrades | Published assets, configuration, migration safety, compatibility policy, and an upgrade path are documented. | Install/update commands and release documentation. | Planned | Fresh-install and upgrade fixture tests. |

## Public API inventory by implementation milestone

The names below are our intended initial surface, not a promise of exact
reference namespaces. Any API that becomes public will receive dedicated tests
and documentation before it is considered stable.

| Milestone | Initial API candidates | Dependencies |
|---|---|---|
| M1 — package loads | `FilamentCloneServiceProvider`, package config, publish tags | Laravel package discovery/Testbench |
| M2 — panels | `Panel`, `PanelProvider`, `PanelRegistry`, `PanelManager`, `PanelResolver` | M1, Laravel router/auth, Livewire |
| M3 — schemas | `Schema`, `Component`, `ComponentContainer`, `ComponentRegistry`, `Get`, `Set` | M2, Blade, Livewire |
| M4 — forms | `Form`, `Field`, basic fields and validation concerns | M3, Laravel validation/Eloquent |
| M5 — tables | `Table`, column/filter contracts, query pipeline | M3, Eloquent/pagination |
| M6 — resources | `Resource`, `ResourceRegistry`, CRUD page bases | M4, M5, policies |
| M7 — actions | `Action`, `ActionGroup`, `ActionManager` | M3, M4/M5/M6 adapters |
| M8 — notifications | `Notification`, delivery/store contracts | M2, Livewire/browser events |
| M9 — widgets/pages/navigation | Widget and page bases; navigation value objects | M2, M3, M8 |
| M10 — plugins | `Plugin` contract, `PluginManager` | M2, M6, M9 |

## Artisan command inventory

Commands will be added in dependency order. Their generated files must use
stubs, validate input, respect application namespaces, avoid silent overwrite,
and have both non-interactive and interactive coverage.

| Command | Expected outcome | Status |
|---|---|---|
| `filament-clone:install` | Publishes/install-configures the package and creates the default panel setup. | Planned |
| `make:filament-clone-panel <id>` | Creates an application panel provider, default route path, and registration guidance. | Planned |
| `make:filament-clone-resource <name>` | Creates resource, CRUD pages, schema and table definition files. | Planned |
| `make:filament-clone-page <name>` | Creates an application custom page and Blade view. | Planned |
| `make:filament-clone-widget <name>` | Creates a widget and view; supports dashboard/table/resource options later. | Planned |
| `make:filament-clone-user` | Creates a user through a safe, validated interactive/non-interactive flow. | Planned |
| `make:filament-clone-plugin <name>` | Creates a plugin contract implementation and package structure. | Planned |
| `filament-clone:assets` | Publishes/builds registered package assets with deterministic versioning. | Planned |

## Application file structures to generate

```text
app/
└── Providers/
    └── FilamentClone/
        └── AdminPanelProvider.php

app/
└── FilamentClone/
    └── Resources/
        └── Customers/
            ├── CustomerResource.php
            ├── Pages/
            │   ├── ListCustomers.php
            │   ├── CreateCustomer.php
            │   ├── EditCustomer.php
            │   └── ViewCustomer.php        # optional
            ├── Schemas/
            │   └── CustomerForm.php
            └── Tables/
                └── CustomersTable.php
```

The exact application namespace and generated paths will be configurable. The
project deliberately uses `FilamentClone` rather than presenting generated code
as official Filament code.

## Route and URL behavior

| Concern | Target behavior |
|---|---|
| Default panel | A configured default panel is reachable at a configurable path, initially `/admin`. |
| Panel isolation | Each route resolves its panel from route/domain configuration; resources/pages/widgets/navigation must not leak across panels. |
| Auth | Guests are sent to the panel’s login route before protected pages render. Logout invalidates the application session safely. |
| Dashboard | Each panel has a named dashboard route and page. |
| Resources | Resource pages use a predictable index/create/view/edit route map, with record parameters resolved through Eloquent and policies. |
| URL generation | Resource/page URLs can target the current panel or an explicit panel ID, encode route parameters safely, and preserve only intentional query state. |
| Table/action state | Search, sort, filter, pagination, and modal action state use explicit Livewire/query-string state; no opaque global state. |
| SPA enhancement | Client-side navigation/prefetching may be introduced after server-rendered routes are complete and tested. |

## Browser, Livewire, CSS, and JavaScript requirements

| Concern | Decision for the clone |
|---|---|
| Server interactivity | Livewire is a first-class dependency for full-page pages, widgets, form state, table state, action modals, and notifications. |
| Browser behavior | Use Alpine-style progressive behavior only where server state is insufficient: menus, focus traps, responsive navigation, dismissals, and small enhancements. |
| State lifecycle | Components must make hydration, dehydration, validation, and query-string synchronization explicit and testable. |
| Styling | Use an original token-based design system with Tailwind-compatible utilities; do not copy reference assets or branding. |
| Accessibility | Keyboard support, focus management, ARIA labeling, error announcements, contrast, reduced motion, and responsive layout are required acceptance criteria. |
| Build assets | Keep JavaScript modular and support a Vite-compatible asset pipeline; package assets must be publishable/versioned. |

## Compatibility boundaries and deferrals

The first package milestone prioritizes an installable panel foundation and
working core CRUD path. These areas are intentionally sequenced after their
dependencies are stable: advanced editors/uploads, import/export, database and
broadcast notifications, full MFA, multi-tenancy, plugin examples, browser test
infrastructure, and performance hardening.

No feature is marked `Verified` until it has implementation, focused tests,
relevant regression coverage, security/performance review, documentation, and a
working example where applicable.

## Reference map

- [Filament 5 documentation index](https://filamentphp.com/docs/llms.txt)
- [Getting started](https://filamentphp.com/docs/5.x/getting-started)
- [Resources overview](https://filamentphp.com/docs/5.x/resources/overview)
- [Schemas overview](https://filamentphp.com/docs/5.x/schemas/overview)
- [Widgets overview](https://filamentphp.com/docs/5.x/widgets/overview)
- [Panel configuration](https://filamentphp.com/docs/5.x/panel-configuration)

These sources are consulted for public behavior and API expectations. This
matrix and all later project documentation are written independently.
