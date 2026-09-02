# Filament Clone — AI Development Guidelines & Skills

> **Purpose:** This document defines how AI assistants, coding agents, and developers must work on the Filament Clone package.
>
> The goal is to build a serious Laravel + Livewire admin-panel package for learning package architecture and eventually extending it with original functionality.

---

# 1. Project Mission

We are building an independent Laravel package that reproduces the **publicly documented developer experience and functionality** of Filament 5 as closely as practical.

The project has two stages:

```text
Stage 1
Functional Filament-style clone
        ↓
Stage 2
Original enhancements and innovations
```

Stage 1 is about learning:

- Laravel package development
- Livewire
- Blade
- Eloquent
- Service containers
- Dependency injection
- fluent APIs
- schema-driven UI
- resource architecture
- table/query abstraction
- form state management
- Artisan generators
- testing
- plugin systems

Stage 2 is where we add functionality that goes beyond the reference package.

---

# 2. Critical Legal / Engineering Boundary

AI MUST NOT:

- Copy Filament source code.
- Copy private/internal implementation details obtained from non-public sources.
- Copy copyrighted source files line-for-line.
- Copy proprietary assets.
- Reproduce Filament branding as if this package were official.
- Claim the package is Filament.
- Copy documentation verbatim.

AI MAY:

- Study publicly documented behavior.
- Study publicly documented APIs.
- Implement compatible concepts independently.
- Create an independent architecture.
- Use public Laravel/Livewire APIs.
- Build tests around expected documented behavior.
- Use Filament documentation as a behavioral reference.

When uncertain, prefer:

```text
same behavior
+
different implementation
+
our own naming where compatibility is not required
```

---

# 3. Primary AI Role

AI is not simply a code generator.

For every task, AI acts as:

```text
Architect
+
Laravel Package Developer
+
Livewire Developer
+
Test Engineer
+
Security Reviewer
+
Documentation Writer
```

AI must understand the existing architecture before modifying it.

Never blindly generate files.

---

# 4. Golden Rule

> **Understand → Design → Implement → Test → Review → Document**

Every non-trivial task follows this sequence.

```text
1. Read existing architecture
2. Identify dependencies
3. Define implementation
4. Implement smallest useful increment
5. Write tests
6. Run tests
7. Review API and edge cases
8. Update documentation
9. Report exactly what changed
```

---

# 5. Repository Exploration Rules

Before changing code, AI MUST inspect:

```text
composer.json
src/
tests/
config/
resources/
routes/
database/
stubs/
docs/
```

For a specific feature, inspect related implementations.

Example:

```text
Implement Table Filters
```

AI should inspect:

```text
Tables/
Filters/
Livewire/
Schemas/
Resources/
Tests/
```

before writing code.

---

# 6. Never Invent Existing APIs

If AI does not know whether a class, method, interface, trait, or configuration key already exists:

```text
SEARCH THE REPOSITORY FIRST.
```

Do not create duplicate concepts.

Bad:

```php
class TableManager {}
```

when the repository already has:

```php
TableRegistry
```

Prefer extending the existing architecture.

---

# 7. Architecture Principles

## 7.1 Single Responsibility

Classes should have one clear responsibility.

Bad:

```text
Resource.php
    database queries
    HTML rendering
    authorization
    notifications
    routing
    validation
```

Prefer:

```text
Resource
ResourceRegistry
ResourcePage
ResourceRouter
ResourceAuthorization
```

---

## 7.2 Composition Over Inheritance

Use inheritance for genuine framework concepts.

Use composition for:

- configuration
- behaviors
- conditions
- actions
- filters
- rendering
- state management

---

## 7.3 Contracts First

For important extension points:

```text
Contract
    ↓
Default implementation
    ↓
Concrete feature
```

Examples:

```text
Panel
PanelProvider
PanelRegistry
```

```text
ActionContract
Action
ActionManager
```

```text
Plugin
PluginManager
```

---

# 8. Public API Stability

Anything intended for package users is a public API.

Examples:

```php
Resource::make()
Table::make()
TextInput::make()
Action::make()
Notification::make()
Panel::make()
```

Before changing a public method:

- [ ] Search usages.
- [ ] Search tests.
- [ ] Search docs.
- [ ] Determine backward compatibility.
- [ ] Add/update tests.
- [ ] Update documentation.

Do not casually rename public methods.

---

# 9. Fluent API Rules

The package should support readable Laravel-style fluent configuration.

Example:

```php
TextInput::make('name')
    ->label('Name')
    ->required()
    ->maxLength(255)
    ->placeholder('Enter name');
```

Rules:

- Methods should have predictable names.
- Fluent methods return `$this` when appropriate.
- Boolean configuration should use readable methods.
- Avoid excessive magic.
- Avoid methods that silently change unrelated behavior.

---

# 10. Schema Architecture

Schemas are one of the core architectural concepts.

Conceptually:

```text
Schema
 ├── Component
 ├── Component
 │    └── Component
 └── Component
```

Every schema component should define:

- identity
- configuration
- state path when applicable
- visibility
- disabled state
- rendering
- lifecycle
- child components when applicable

AI must avoid putting form-specific logic directly into the generic Schema engine.

Use:

```text
Schema
Form
Infolist
```

as separate consumers of shared schema infrastructure.

---

# 11. State Management Rules

State is a first-class concern.

AI must distinguish:

```text
UI configuration
vs
component state
vs
record/model state
vs
request state
vs
session state
```

Do not mix these casually.

State operations should be explicit:

```text
hydrate
get
set
dehydrate
validate
reset
```

---

# 12. Utility Injection

When closures are accepted, support controlled dependency injection.

Potential utilities:

```text
Get
Set
Component
Livewire
Record
Operation
```

Example:

```php
->visible(fn (Get $get) => $get('type') === 'advanced')
```

Rules:

- Resolve dependencies through Laravel's container where appropriate.
- Avoid global state.
- Avoid static mutable state.
- Keep closure evaluation testable.

---

# 13. Form Component Rules

Every form field should ideally support a common contract:

```text
Field
 ├── name
 ├── label
 ├── default
 ├── required
 ├── disabled
 ├── hidden
 ├── helper text
 ├── validation
 ├── state
 └── rendering
```

Advanced fields may add:

```text
search
multiple
relationship
options
upload
repeater
builder
```

Do not duplicate common behavior in every field.

Use reusable concerns/traits where justified.

---

# 14. Table Architecture

Tables should be separated into:

```text
Table
 ├── Query
 ├── Columns
 ├── Filters
 ├── Actions
 ├── Pagination
 ├── Selection
 └── State
```

Never make a table component responsible for arbitrary database business logic.

Query building should remain composable.

Example:

```text
Base query
   ↓
Search
   ↓
Filters
   ↓
Scopes
   ↓
Sort
   ↓
Pagination
```

---

# 15. Query Safety

AI MUST NOT construct SQL using untrusted string interpolation.

Bad:

```php
$query->whereRaw("name = '$value'");
```

Prefer:

```php
$query->where('name', $value);
```

or parameterized expressions.

All dynamic filters and searches must be reviewed for:

- SQL injection
- authorization
- tenant isolation
- performance

---

# 16. Resource Rules

Resources are the high-level CRUD abstraction.

Recommended dependency direction:

```text
Resource
   ↓
Resource Pages
   ↓
Forms / Schemas
Tables
Actions
```

A Resource should describe the domain.

It should not become a giant service class.

Example:

```php
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable(),
        ]);
    }
}
```

---

# 17. Livewire Rules

Livewire components should remain understandable.

AI must consider:

- public properties
- hydration
- dehydration
- lifecycle hooks
- validation
- events
- browser events
- URL parameters
- query-string state
- loading state

Avoid:

- giant `render()` methods
- hidden state mutations
- excessive browser JavaScript
- unnecessary full-page refreshes

---

# 18. Blade Rules

Blade should primarily render UI.

Avoid putting business logic into Blade.

Bad:

```blade
@php
    // complex database logic
@endphp
```

Prefer:

```text
PHP component
    ↓
prepared state
    ↓
Blade
```

Blade should remain readable.

---

# 19. JavaScript Rules

JavaScript should only own behavior that genuinely belongs in the browser.

Prefer:

```text
Livewire state
+
Alpine behavior
```

for interactive UI.

Avoid adding a large JavaScript framework unless architecture requires it.

Keep package JS modular:

```text
resources/js/
    app.js
    components/
    utilities/
```

---

# 20. CSS / Tailwind Rules

Use utility classes where appropriate.

Do not create massive custom CSS files for simple layouts.

Centralize design tokens.

Examples:

```text
colors
spacing
radius
shadows
typography
```

The UI can be visually inspired by modern admin interfaces, but assets and branding should be original.

---

# 21. Artisan Generator Rules

Generators are part of the developer experience.

Every generator should:

- [ ] Support interactive use.
- [ ] Support non-interactive use.
- [ ] Validate names.
- [ ] Generate correct namespaces.
- [ ] Use stubs.
- [ ] Respect project structure.
- [ ] Avoid overwriting unexpectedly.
- [ ] Have tests.

Example:

```bash
php artisan make:filament-clone-resource Product
```

Generated code must itself follow package standards.

---

# 22. Plugin Architecture

Plugins must not require modifying core source code.

Target:

```text
Plugin
   ↓
Plugin Manager
   ↓
Panel
   ├── Resources
   ├── Pages
   ├── Widgets
   ├── Navigation
   └── Assets
```

Plugins should be able to register capabilities through public contracts.

Avoid tightly coupling plugins to internal implementation classes.

---

# 23. Testing Rules

No important feature is complete without tests.

Testing layers:

```text
Unit
 ↓
Integration
 ↓
Feature
 ↓
Livewire
 ↓
Browser
```

Use the lowest appropriate level.

---

# 24. Test Naming

Tests should describe behavior.

Good:

```text
it_creates_a_product_from_the_resource_form
```

Bad:

```text
test_method_123
```

Test:

- expected behavior
- failure behavior
- authorization
- validation
- edge cases

---

# 25. Required Test Categories

For a typical feature:

```text
Happy path
Invalid input
Unauthorized user
Missing record
Empty state
Large dataset
Relationship
Reactive behavior
Browser interaction
```

Not every feature requires every category, but AI must consciously evaluate each.

---

# 26. Security Rules

Security is mandatory.

Review:

### Authentication

- [ ] Session security
- [ ] Password handling
- [ ] Logout
- [ ] Remember-me behavior

### Authorization

- [ ] Policies
- [ ] Gates
- [ ] Resource permissions
- [ ] Action permissions

### Data

- [ ] Mass assignment
- [ ] IDOR
- [ ] Tenant isolation
- [ ] Sensitive attributes

### Web

- [ ] CSRF
- [ ] XSS
- [ ] SQL injection
- [ ] unsafe HTML

### Files

- [ ] MIME validation
- [ ] file size
- [ ] filename handling
- [ ] storage permissions
- [ ] executable uploads

---

# 27. Performance Rules

AI must consider performance before adding database operations.

Watch for:

```text
N+1 queries
large Livewire payloads
unnecessary hydration
repeated reflection
repeated schema resolution
expensive table queries
unbounded searches
```

For large tables:

```text
query
 ↓
filters
 ↓
pagination
```

should happen at the database level.

Do not load thousands of records into PHP merely to paginate them.

---

# 28. Caching Rules

Cache only when there is a clear benefit.

Potential cache targets:

- discovered resources
- panel configuration
- generated metadata
- static component metadata
- navigation

Never cache mutable user-specific state globally.

Be careful with:

```text
tenant
user
authorization
session
```

---

# 29. Error Handling

Errors should be:

```text
specific
actionable
safe
testable
```

Avoid:

```php
catch (Throwable $e) {
    // ignore
}
```

Never silently swallow exceptions unless the behavior is intentional and documented.

---

# 30. Logging

Use Laravel logging where appropriate.

Do not log:

- passwords
- tokens
- secrets
- session data
- sensitive personal information

Provide useful context without exposing sensitive values.

---

# 31. Documentation Rules

Every public feature needs documentation.

Minimum:

```text
What it does
Installation/setup
Basic example
Configuration
Advanced example
Common errors
Testing
```

Documentation should be written in our own words.

---

# 32. Dependency Rules

Before adding a dependency:

AI must answer:

1. Why is it needed?
2. Can Laravel provide this?
3. Can Livewire provide this?
4. Is it maintained?
5. What is its license?
6. What is its runtime cost?
7. Does it increase package coupling?

Avoid dependencies for trivial functionality.

---

# 33. Version Compatibility

Track:

```text
PHP
Laravel
Livewire
Tailwind
Node
```

Use Composer constraints carefully.

Do not silently depend on APIs introduced in newer versions.

---

# 34. Database Rules

Package migrations should:

- use safe names
- avoid conflicts
- be publishable where appropriate
- be tested
- avoid destructive behavior by default

Never silently drop or destroy application data.

---

# 35. Naming Rules

Prefer clear names.

Good:

```text
PanelRegistry
ResourceRegistry
ComponentResolver
ActionManager
NotificationManager
```

Avoid vague names:

```text
Helper
Manager2
Utils
Thing
Processor
```

unless their scope is genuinely broad.

---

# 36. Directory Rules

Use domain-oriented structure.

Preferred:

```text
src/
    Panels/
    Resources/
    Schemas/
    Tables/
    Forms/
    Actions/
    Notifications/
    Widgets/
```

Avoid dumping unrelated classes into:

```text
src/Helpers/
src/Misc/
src/Services/
```

without a clear architectural reason.

---

# 37. Traits / Concerns

Traits are allowed for shared behavior.

Good candidates:

```text
HasLabel
HasIcon
CanBeHidden
CanBeDisabled
HasDescription
HasActions
HasState
```

Do not use traits merely to avoid creating a proper abstraction.

---

# 38. Static State Rules

Avoid mutable static state.

Bad:

```php
static::$components[] = $component;
```

when this can leak between requests/tests.

Prefer:

```text
registry instance
panel instance
container-bound manager
```

When static APIs are necessary for developer ergonomics, keep the underlying state isolated and testable.

---

# 39. Configuration Rules

Configuration should be predictable.

Distinguish:

```text
package defaults
panel configuration
resource configuration
runtime state
user state
```

Do not put runtime state into config files.

---

# 40. Backward Compatibility

Before changing behavior:

```text
Search
 ↓
Tests
 ↓
Docs
 ↓
Deprecation strategy
 ↓
Migration path
```

Prefer deprecation over sudden removal.

---

# 41. AI Task Execution Protocol

Every implementation request should be converted into:

```text
Task
Objective
Existing architecture
Dependencies
Files to change
Implementation steps
Tests
Acceptance criteria
Risks
```

Then implement.

---

# 42. Standard AI Task Template

Use this internally for every task:

```markdown
# Task: <name>

## Objective

<what we need to achieve>

## Why

<architectural reason>

## Existing Components

- <class>
- <interface>
- <trait>

## Files to Create

- <file>

## Files to Modify

- <file>

## API

```php
// intended public API
```

## Implementation

1. ...
2. ...
3. ...

## Tests

- [ ] ...
- [ ] ...

## Acceptance Criteria

- [ ] ...
- [ ] ...

## Risks

- ...
```

---

# 43. AI Implementation Workflow

For every task:

### Step 1 — Understand

Read the relevant source.

### Step 2 — Map dependencies

Determine:

```text
who calls this?
what does it call?
what state does it own?
what is public?
```

### Step 3 — Design

Write the smallest architecture that fits.

### Step 4 — Implement

Implement only the requested scope.

### Step 5 — Test

Run focused tests first.

### Step 6 — Regression

Run the relevant full suite.

### Step 7 — Review

Check:

- API
- security
- performance
- naming
- architecture

### Step 8 — Document

Update docs/examples if public behavior changed.

---

# 44. AI Must Avoid Scope Creep

If the task is:

```text
Implement TextInput
```

do not also implement:

```text
Select
Repeater
Tables
Notifications
Themes
```

unless they are required dependencies.

Finish the smallest coherent unit.

---

# 45. AI Must Prefer Vertical Slices

Instead of building:

```text
100 classes with no working feature
```

prefer:

```text
Panel
 ↓
Login
 ↓
Dashboard
 ↓
Resource
 ↓
Form
 ↓
Table
 ↓
CRUD
```

Each milestone should produce something executable.

---

# 46. Definition of Done

A feature is DONE only when:

- [ ] Implementation exists.
- [ ] Public API is intentional.
- [ ] Unit tests exist where useful.
- [ ] Feature/integration tests exist where useful.
- [ ] Edge cases considered.
- [ ] Authorization considered.
- [ ] Performance considered.
- [ ] Documentation updated.
- [ ] Existing tests pass.
- [ ] No obvious duplicate abstraction exists.

---

# 47. Feature Scorecard

Use this scorecard:

| Category | Complete |
|---|---:|
| Architecture | [ ] |
| Implementation | [ ] |
| API | [ ] |
| Tests | [ ] |
| Validation | [ ] |
| Authorization | [ ] |
| Security | [ ] |
| Performance | [ ] |
| Documentation | [ ] |
| Example | [ ] |

A feature should not be marked complete merely because its UI renders.

---

# 48. Filament Clone Architecture Map

The long-term architecture should roughly evolve toward:

```text
FilamentClone
│
├── Panel
│   ├── PanelProvider
│   ├── PanelRegistry
│   ├── PanelManager
│   ├── Routes
│   ├── Middleware
│   ├── Authentication
│   └── Navigation
│
├── Resources
│   ├── Resource
│   ├── ResourceRegistry
│   ├── Pages
│   ├── RelationManagers
│   └── Concerns
│
├── Schemas
│   ├── Schema
│   ├── Components
│   ├── Layouts
│   ├── State
│   └── Utilities
│
├── Forms
│   ├── Form
│   ├── Fields
│   ├── Validation
│   └── State
│
├── Tables
│   ├── Table
│   ├── Columns
│   ├── Filters
│   ├── Actions
│   └── Query
│
├── Infolists
│   ├── Infolist
│   └── Entries
│
├── Actions
│   ├── Action
│   ├── ActionGroup
│   └── Modals
│
├── Notifications
│   ├── Notification
│   └── Delivery
│
├── Widgets
│   ├── Widget
│   ├── Stats
│   ├── Charts
│   └── Tables
│
├── Components
│   ├── Blade
│   ├── Livewire
│   └── UI
│
└── Plugins
    ├── Plugin
    ├── PluginManager
    └── Registration
```

---

# 49. Dependency Direction

Prefer:

```text
Support
  ↑
Schemas
  ↑
Forms / Infolists / Tables
  ↑
Actions
  ↑
Resources
  ↑
Panels
```

Avoid circular dependencies.

For example:

```text
Schema → Resource
```

should generally be avoided.

Prefer:

```text
Resource → Schema
```

---

# 50. Core Interfaces

Potential long-term contracts:

```php
interface PanelProvider
{
    public function panel(Panel $panel): Panel;
}
```

```php
interface ResourceContract
{
    public static function getModel(): string;
}
```

```php
interface ComponentContract
{
    public function render(): View;
}
```

```php
interface Plugin
{
    public function getId(): string;

    public function register(Panel $panel): void;

    public function boot(Panel $panel): void;
}
```

These are architectural examples; adapt them to the actual implementation rather than blindly copying them.

---

# 51. Internal Service Layer

Use Laravel's container for major services:

```text
PanelManager
ResourceRegistry
ComponentRegistry
ActionManager
NotificationManager
PluginManager
```

Example:

```php
app(PanelManager::class);
```

or constructor injection.

Avoid manually creating complex service graphs everywhere.

---

# 52. Event Architecture

Use events when decoupling is valuable.

Potential events:

```text
PanelRegistered
ResourceRegistered
RecordCreating
RecordCreated
RecordSaving
RecordSaved
RecordDeleting
RecordDeleted
ActionExecuting
ActionExecuted
NotificationSent
```

Do not create events for every method call.

---

# 53. Extension Points

Public extension points should be intentional.

Examples:

```text
registering
booting
render hooks
custom fields
custom columns
custom filters
custom actions
custom widgets
plugins
themes
navigation
```

Document every extension point.

---

# 54. Observability

For difficult debugging, provide optional development tooling:

```text
panel inspector
resource inspector
schema inspector
query diagnostics
Livewire state diagnostics
```

These belong in the enhancement phase unless needed earlier.

---

# 55. AI Code Review Checklist

Before finalizing code, AI must mentally review:

## Architecture

- Is this the correct layer?
- Is there an existing abstraction?
- Is there unnecessary coupling?

## API

- Is the method name consistent?
- Is the return type correct?
- Is fluent behavior predictable?

## Laravel

- Does it use Laravel facilities appropriately?
- Is dependency injection used correctly?
- Is Eloquent used correctly?

## Livewire

- Is state serializable?
- Are lifecycle hooks correct?
- Is the payload reasonable?

## Security

- Can a user bypass authorization?
- Is input trusted?
- Is tenant isolation preserved?

## Performance

- Any N+1?
- Any unnecessary query?
- Any repeated expensive work?

## Tests

- Does the behavior have coverage?
- Is the failure case covered?

---

# 56. AI Response Format for Implementation Tasks

When asked to implement a task, respond with:

```text
## Understanding

<short interpretation>

## Plan

1. ...
2. ...
3. ...

## Implementation

<changes made>

## Tests

<tests added/run>

## Verification

<result>

## Next Task

<logical next step>
```

Do not provide huge amounts of unrelated explanation.

---

# 57. When AI Should Ask Questions

AI should ask only when the ambiguity materially affects architecture.

Ask if:

- package name is unknown and affects Composer namespace
- supported Laravel version is unknown
- API compatibility choice is unclear
- two architectures have materially different consequences

Do NOT ask for trivial details that can be reasonably inferred from the existing project.

---

# 58. When AI Should Make Decisions

AI should make a sensible choice when:

- naming is internal
- implementation detail is not public
- test structure is obvious
- a standard Laravel pattern applies
- the existing repository establishes a convention

Explain the decision briefly when it matters.

---

# 59. Learning Mode

Because this project is primarily for learning, AI should explain important architectural decisions.

For example:

```text
We are using a Registry here because multiple panels need
independent Resource collections. A global static array would
make tests and multi-panel behavior harder to reason about.
```

Do not explain trivial syntax.

Focus explanations on:

- architecture
- Laravel internals
- Livewire lifecycle
- package design
- tradeoffs
- extensibility

---

# 60. Do Not Hide Complexity

If a feature is difficult, AI should not fake a simplified implementation and call it complete.

Instead:

```text
Phase 1
Minimal working implementation

Phase 2
Edge cases

Phase 3
Compatibility hardening
```

Make the limitation explicit.

---

# 61. Reference Documentation Workflow

When implementing a feature corresponding to documented Filament behavior:

```text
Public documentation
       ↓
Behavioral requirements
       ↓
Our architecture
       ↓
Implementation
       ↓
Tests
```

The documentation is a **behavioral reference**, not source code to reproduce.

---

# 62. Compatibility Matrix

Maintain:

```text
docs/compatibility-matrix.md
```

For every feature:

| Feature | Reference behavior | Our API | Status | Tests |
|---|---|---|---|---|
| Panel | documented | implemented | 🟡 | [ ] |
| Resource | documented | implemented | 🟡 | [ ] |
| Form | documented | implemented | 🟡 | [ ] |
| Table | documented | implemented | 🟡 | [ ] |
| Action | documented | implemented | 🟡 | [ ] |
| Widget | documented | implemented | 🟡 | [ ] |
| Notification | documented | implemented | 🟡 | [ ] |
| Plugin | documented | implemented | 🟡 | [ ] |

---

# 63. Milestone Strategy

Never attempt the entire package in one pass.

Milestones:

```text
M1 Package loads
M2 Panel works
M3 Schema works
M4 Form works
M5 Table works
M6 Resource CRUD works
M7 Actions work
M8 Notifications work
M9 Widgets/pages work
M10 Plugins work
M11 Testing complete
M12 Release candidate
```

Each milestone should be tagged in Git.

---

# 64. Git Guidelines

Use small commits.

Recommended format:

```text
feat(panel): add panel registry
feat(schema): add component state
feat(forms): add text input
feat(tables): add sorting
feat(resources): add create page
test(actions): cover confirmation modal
fix(tables): prevent duplicate joins
docs(resources): document custom pages
```

Avoid:

```text
big changes
update stuff
final
done
```

---

# 65. Branch Strategy

Recommended:

```text
main
develop
feature/*
fix/*
```

For learning, even if only `main` is used locally, commits should remain logically small.

---

# 66. Release Strategy

Version using SemVer:

```text
MAJOR.MINOR.PATCH
```

Example:

```text
0.1.0
0.2.0
0.3.0
1.0.0
```

Before 1.0:

- API may evolve.
- Breaking changes must still be documented.

After 1.0:

- protect public APIs carefully.

---

# 67. Package Quality Gates

Before `1.0.0`:

- [ ] Fresh install.
- [ ] Laravel compatibility.
- [ ] Livewire compatibility.
- [ ] Full test suite.
- [ ] Static analysis.
- [ ] Code style.
- [ ] Security review.
- [ ] Documentation.
- [ ] Example application.
- [ ] Package discovery.
- [ ] Asset publishing.
- [ ] Artisan generators.

---

# 68. Skill: Laravel Package Architect

AI using this skill must:

1. Think in package boundaries.
2. Prefer contracts for extension points.
3. Use service providers correctly.
4. Use Laravel's container.
5. Keep configuration isolated.
6. Make package installation reproducible.
7. Test package behavior using Testbench.
8. Protect public APIs.

---

# 69. Skill: Livewire Architect

AI using this skill must:

1. Understand hydration/dehydration.
2. Keep public state intentional.
3. Minimize payload size.
4. Use lifecycle hooks correctly.
5. Validate server-side.
6. Treat authorization as server-side.
7. Avoid unnecessary browser state.
8. Test Livewire behavior.

---

# 70. Skill: Schema Engineer

AI using this skill must:

1. Build reusable components.
2. Separate configuration from state.
3. Support nested components.
4. Support conditional behavior.
5. Support utility injection.
6. Keep schema rendering generic.
7. Avoid coupling to Eloquent unless required.

---

# 71. Skill: Form Engineer

AI using this skill must:

1. Build on the Schema system.
2. Keep field APIs consistent.
3. Support validation.
4. Support state lifecycle.
5. Support relationships.
6. Handle nested data.
7. Secure file uploads.
8. Test reactive behavior.

---

# 72. Skill: Table Engineer

AI using this skill must:

1. Build database queries server-side.
2. Support search/filter/sort.
3. Avoid N+1 queries.
4. Support pagination.
5. Make columns composable.
6. Keep filters composable.
7. Protect bulk actions with authorization.

---

# 73. Skill: Resource Engineer

AI using this skill must:

1. Treat Resource as configuration/domain metadata.
2. Delegate forms to schemas.
3. Delegate tables to table engine.
4. Delegate actions to action engine.
5. Respect Laravel policies.
6. Keep page lifecycle understandable.

---

# 74. Skill: UI Component Engineer

AI using this skill must:

1. Build accessible components.
2. Support responsive behavior.
3. Keep Blade simple.
4. Keep component APIs consistent.
5. Avoid unnecessary JavaScript.
6. Support keyboard interaction.
7. Consider dark mode.

---

# 75. Skill: Test Engineer

AI using this skill must:

1. Test behavior, not implementation details.
2. Prefer focused tests.
3. Cover authorization.
4. Cover validation.
5. Cover edge cases.
6. Protect public APIs.
7. Run regression tests.

---

# 76. Skill: Security Engineer

AI using this skill must:

1. Assume client state is untrusted.
2. Authorize every sensitive operation.
3. Validate uploads.
4. Prevent SQL injection.
5. Prevent XSS.
6. Protect tenant boundaries.
7. Avoid logging secrets.

---

# 77. Skill: Performance Engineer

AI using this skill must:

1. Inspect SQL queries.
2. Detect N+1 problems.
3. Avoid unnecessary Livewire state.
4. Use pagination.
5. Cache carefully.
6. Measure before optimizing.
7. Avoid premature abstraction.

---

# 78. Skill: Documentation Engineer

AI using this skill must:

1. Document public APIs.
2. Provide examples.
3. Explain configuration.
4. Document extension points.
5. Document breaking changes.
6. Keep examples executable where possible.

---

# 79. Standard Feature Development Example

Task:

```text
Add a Toggle form field.
```

AI process:

```text
1. Inspect Schema
2. Inspect existing Field base class
3. Inspect TextInput / Checkbox
4. Design Toggle
5. Implement Toggle
6. Add Blade view
7. Add state behavior
8. Add disabled/hidden support
9. Add validation integration
10. Add Livewire test
11. Add rendering test
12. Update docs
13. Run regression suite
```

---

# 80. Standard Bug Fix Example

Task:

```text
Table filtering causes duplicate records.
```

AI process:

```text
1. Reproduce
2. Add failing test
3. Inspect query pipeline
4. Identify join/filter issue
5. Fix smallest layer
6. Run focused test
7. Run table suite
8. Check related Resource behavior
9. Document if public behavior changed
```

Never fix a bug by merely hiding the symptom in the UI.

---

# 81. Standard API Addition Example

Task:

```text
Add ->searchable()
```

AI must determine:

```text
Who owns this behavior?
Column?
Table?
Query builder?
```

Expected architecture:

```text
TextColumn
    ↓
search configuration
    ↓
Table
    ↓
Query builder
```

not:

```text
TextColumn
    ↓
raw SQL execution
```

---

# 82. Standard New Component Example

For a new component:

```text
Component contract
      ↓
Base component
      ↓
configuration
      ↓
state
      ↓
Blade view
      ↓
Livewire integration
      ↓
tests
```

---

# 83. Standard New Resource Example

For a Resource:

```text
Model
 ↓
Resource
 ├── Form Schema
 ├── Table
 ├── Infolist
 ├── Pages
 ├── Actions
 └── Authorization
```

Do not put everything into the Resource class.

---

# 84. AI Must Keep a Decision Log

Maintain:

```text
docs/architecture-decisions/
```

Record important decisions:

```text
ADR-001-package-namespace.md
ADR-002-panel-registry.md
ADR-003-schema-state.md
ADR-004-table-query-pipeline.md
ADR-005-plugin-system.md
```

Each ADR:

```text
Context
Decision
Alternatives
Consequences
```

---

# 85. Architecture Review Triggers

AI should stop and review architecture before implementing if:

- A class exceeds reasonable responsibility.
- A new global singleton is proposed.
- A circular dependency appears.
- A public API needs breaking.
- A feature requires modifying many unrelated modules.
- A feature cannot be tested independently.
- A database query needs raw SQL unexpectedly.
- A UI feature requires excessive JavaScript.

---

# 86. Anti-Patterns

Avoid:

```text
God classes
God traits
global mutable state
static registries with test leakage
business logic in Blade
database queries in views
unbounded queries
silent exception swallowing
magic strings everywhere
copy-pasted field logic
copy-pasted authorization
duplicate registries
unnecessary dependencies
premature optimization
```

---

# 87. Project Definition of "Good Code"

Good code is:

```text
Readable
Predictable
Testable
Composable
Secure
Performant
Extensible
Documented
```

Not merely:

```text
short
clever
magical
```

---

# 88. Final AI Operating Principle

When uncertain, prefer the implementation that is:

```text
simpler
+
more explicit
+
more testable
+
more Laravel-native
+
less coupled
+
easier to extend
```

over:

```text
clever
+
magical
+
tightly coupled
+
hard to test
```

---

# 89. Master Development Loop

Every day of the 80-day implementation roadmap should follow:

```text
┌───────────────────────┐
│ Read today's task     │
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Inspect architecture  │
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Design smallest slice│
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Implement             │
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Write tests            │
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Run tests              │
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Security/performance  │
│ review                 │
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Document               │
└──────────┬────────────┘
           ↓
┌───────────────────────┐
│ Commit                 │
└──────────┬────────────┘
           ↓
       NEXT TASK
```

---

# 90. Final Rule

> **Never optimize for "generate lots of code." Optimize for "build a maintainable package one verified architectural slice at a time."**

The objective is not to produce 1000 files.

The objective is to understand why each file exists, how it connects to the rest of the framework, and how another Laravel developer can extend it without modifying the core.

That is the standard AI must follow for the entire Filament Clone project.
