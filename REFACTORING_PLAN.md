# GiftFlow v2.0 — Refactoring Plan

## Overview

Refactor GiftFlow to follow WordPress 2026 standards: easy to use, user-friendly, developer-friendly, block theme first. Clear folder/file structure, consistent classes/functions, Gutenberg-style settings with inheritance.

---

## Current Pain Points

| Area | Problem |
|------|---------|
| **Loading** | ~30 manual `require_once` calls, no PSR-4 autoloading |
| **Structure** | Mixed namespaces (`GiftFlow\*` vs root global classes `GiftFlow_Field`), 1743-line `common.php` junk drawer |
| **Blocks** | No `block.json`, no Interactivity API, no `supports`, PHP-array registration |
| **Settings** | 655-line procedural function, no visual Gutenberg-style UI |
| **Templates** | Dual classic + FSE paths, `extract()` usage, inconsistent naming |
| **Build** | Legacy Laravel Mix instead of `@wordpress/scripts` |
| **Gateways** | Monolithic 2957-line PayPal class, no interface segregation |
| **Data** | Raw `php://input`, no REST API schema, no `@wordpress/data` |

---

## Phase 1: Foundation & Structure (M1) ✅ COMPLETE

### 1.1 New Directory Structure

```
giftflow/
├── giftflow.php                          # Bootstrap only: constants + Composer autoload
├── composer.json                         # PSR-4 autoload, wp-scripts dev deps
├── package.json                          # @wordpress/scripts native build
├── readme.txt
│
├── src/                                  # All PHP source (namespaced)
│   ├── Core/
│   │   ├── Plugin.php                    # Main plugin class (replaces Loader)
│   │   ├── Container.php                 # PSR-11 service container
│   │   ├── AbstractModule.php            # Base class for all modules
│   │   ├── AssetLoader.php               # Script/style registration
│   │   └── HookManager.php               # Centralized hook constants
│   │
│   ├── PostTypes/                        # (M2+)
│   │   └── AbstractPostType.php
│   │
│   ├── Meta/                             # (M2+)
│   │   └── AbstractMetaBox.php
│   │
│   ├── Gateways/                         # (M4)
│   │   ├── GatewayInterface.php
│   │   └── AbstractGateway.php
│   │
│   ├── Settings/                         # (M3)
│   │   └── SettingsRegistry.php
│   │
│   ├── REST/                             # (M5)
│   │   └── Controller.php
│   │
│   ├── Templates/                        # (M5)
│   │   └── TemplateLoader.php
│   │
│   ├── Services/                         # (M5+)
│   │   ├── CurrencyService.php
│   │   ├── EmailService.php
│   │   └── LogService.php
│   │
│   └── Functions/                        # Split common.php into domain files
│       ├── helpers.php
│       ├── currency.php
│       ├── campaigns.php
│       └── templates.php
│
├── blocks/                               # All Gutenberg blocks
│   ├── donation-button/
│   │   ├── block.json                    # Modern block registration
│   │   ├── render.php                    # Render callback
│   │   ├── view.js                       # Interactivity API
│   │   ├── edit.js                       # Editor component
│   │   └── style.css                     # Block styles
│   ├── campaign-status-bar/
│   ├── campaign-single-content/
│   ├── campaign-single-images/
│   ├── campaigns-grid/
│   ├── donor-account/
│   ├── share/
│   └── thank-donor/
│
├── settings/                             # Gutenberg-style settings UI
│   ├── block.json                        # Settings page as a block
│   ├── index.js                          # React settings app
│   └── components/
│
├── templates/                            # FSE block templates
│   ├── single-campaign.html
│   ├── taxonomy-campaign-tax.html
│   ├── page-campaigns.html
│   ├── page-donor-account.html
│   └── page-thank-donor.html
│
├── build/                                # Compiled assets (auto-generated)
│
├── vendor/                               # Composer deps (dev only)
└── vendor-prefixed/                      # Strauss-prefixed deps (production)
```

### 1.2 Autoloading (PSR-4)

```json
{
  "autoload": {
    "psr-4": {
      "GiftFlow\\": "src/"
    }
  }
}
```

- All classes follow `GiftFlow\{Module}` namespace matching directory.
- Remove all manual `require_once` from `giftflow_load_files()`.
- `giftflow.php` only loads Composer autoloader + instantiates `Plugin`.
- Production fallback: inline `spl_autoload_register` mirrors PSR-4 when `vendor/` is absent.

### 1.3 Container-Based Bootstrap

```php
namespace GiftFlow\Core;

class Plugin {
    private Container $container;

    public function boot(): void {
        add_action('plugins_loaded', function() {
            $this->container->get(AssetLoader::class)->register();
            // ...
        });
    }
}
```

Benefits: Testable, swappable dependencies, no global state.

### 1.4 Backward Compatibility

- `giftflow_load_files()` still called internally for legacy classes.
- `giftflow_load_files` filter still supported.
- Existing post types, meta keys, option names unchanged.
- New classes use `src/` with PSR-4; legacy classes remain in `includes/` and `admin/`.

---

## Phase 2: Block System Modernization (M2) 🔜 NEXT

### 2.1 `block.json` for All Blocks

**Today**: PHP array → `register_block_type('giftflow/donation-button', [...])`  
**Target**: `block.json` → `register_block_type_from_metadata(__DIR__ . '/blocks/donation-button/block.json')`

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "giftflow/donation-button",
  "title": "Donation Button",
  "category": "giftflow",
  "supports": {
    "color": { "background": true, "text": true },
    "spacing": { "margin": true, "padding": true },
    "typography": { "fontSize": true },
    "align": true
  },
  "usesContext": ["postId", "postType"],
  "render": "file:./render.php",
  "viewScript": "file:./view.js",
  "editorScript": "file:./edit.js",
  "style": "file:./style.css"
}
```

### 2.2 Interactivity API for Reactive Blocks

Replace ad-hoc `wp_is_serving_rest_request()` heuristics with proper Interactivity API directives:

```html
<div
  <?php echo wp_interactivity_data_wp_context(['campaignId' => $campaign_id]); ?>
  data-wp-interactive="giftflow/donation-button"
  data-wp-on--click="actions.openModal"
>
  <button><?php esc_html_e('Donate Now', 'giftflow'); ?></button>
</div>
```

### 2.3 Block Supports & Global Styles

- Every block declares `supports` for `color`, `spacing`, `typography`, `align`.
- Register a `theme.json` in the plugin root for default styles:

```json
{
  "version": 3,
  "styles": {
    "blocks": {
      "giftflow/donation-button": {
        "color": { "background": "var(--wp--preset--color--primary)" },
        "typography": { "fontSize": "var(--wp--preset--font-size--medium)" }
      }
    }
  },
  "blockTypes": {
    "giftflow/campaigns-grid": {
      "spacing": { "blockGap": "var(--wp--preset--spacing--40)" }
    }
  }
}
```

### 2.4 State Management with `@wordpress/data`

```js
import { createReduxStore, register } from '@wordpress/data';

const store = createReduxStore('giftflow/donations', {
  reducer(state, action) { /* ... */ },
  actions: { addDonation(d) { /* ... */ } },
  selectors: { getDonationsByCampaign(state, id) { /* ... */ } },
  resolvers: { *getDonationsByCampaign(id) { /* fetch from REST */ } },
});

register(store);
```

### 2.5 Blocks to Migrate

| Block | JS | PHP | Notes |
|-------|----|-----|-------|
| `giftflow/donation-button` | Yes | Yes | Has modal, gateway selection |
| `giftflow/campaign-status-bar` | Yes | Yes | Progress bar with `__editorPostId` |
| `giftflow/campaign-single-content` | Yes | Yes | Tabs: Campaign/Donations/Comments |
| `giftflow/campaign-single-images` | Yes | Yes | Featured image + gallery + lightbox |
| `giftflow/campaigns-grid` | Yes | Yes | Query params for filtering |
| `giftflow/donor-account` | Yes | Yes | Tabs: Dashboard/My Donations/My Account |
| `giftflow/share` | Yes | Yes | Social share buttons |
| `giftflow/thank-donor` | Yes | Yes | Configurable thank-you message |

---

## Phase 3: Gutenberg-Style Settings (M3)

### 3.1 Concept: Inheritance Model

```
Plugin Global Defaults
    ↓ (override)
Campaign-Specific Settings  
    ↓ (override)
Block Instance Attributes
```

Each setting level inherits from the level above. Example:

```
Global: Currency = USD, Default Amount = $25
  └─ Campaign "Water Project": Currency = EUR (inherits), Default Amount = €50
       └─ Block on homepage: Currency = EUR (inherits), Default Amount = €100 (override)
```

### 3.2 Settings Storage

```php
$settings = [
    'global' => [
        'currency' => 'USD',
        'default_amount' => 2500,         // cents
        'allow_custom_amount' => true,
        'preset_amounts' => [1000, 2500, 5000, 10000],
        'payment_gateways' => ['stripe', 'paypal'],
    ],
    'display' => [
        'show_progress_bar' => true,
        'show_donor_count' => true,
        'grid_columns' => 3,
        'primary_color' => '#005ae0',
        'border_radius' => 8,
    ],
    'email' => [
        'from_name' => '...',
        'subject_template' => '...',
    ],
];
```

### 3.3 Settings UI (Gutenberg-Style)

Instead of legacy WP settings pages, build the settings as a React app using WP admin components:

```
┌──────────────────────────────────────────────────────┐
│  GiftFlow Settings                                    │
├────────────┬─────────────────────────────────────────┤
│ "General"  │  ┌─────────────────────────────────┐   │
│ "Payment"  │  │  Currency                        │   │
│ "Display"  │  │  [USD ▼]                        │   │
│ "Email"    │  │                                  │   │
│ "Advanced" │  │  Default Donation Amount         │   │
│            │  │  [_______25________]             │   │
│            │  │                                  │   │
│            │  │  Preset Amounts                  │   │
│            │  │  [$10] [$25] [$50] [$100] [+Add] │   │
│            │  │                                  │   │
│            │  │  □ Allow Custom Amount           │   │
│            │  └─────────────────────────────────┘   │
├────────────┴─────────────────────────────────────────┤
│ "GiftFlow" plugin admin panel                        │
└──────────────────────────────────────────────────────┘
```

Uses:
- `@wordpress/components` (Panel, ToggleControl, SelectControl, ColorPicker)
- `@wordpress/data` for state
- `@wordpress/api-fetch` for saving

### 3.4 Inheritance in Block Editor

In the block editor sidebar, each GiftFlow block shows inherited settings:

```
┌─ Block ▸──────────────┐
│ Campaign Status Bar   │
│                       │
│ ▸ Display Settings ───│
│   Progress Bar Color  │
│     Inherited: #005ae0│  ← dimmed, from global
│   [Custom ▸]          │  ← toggle to override
│                       │
│ ▸ Campaign Settings ──│
│   Campaign ID         │
│   [____123____]       │
└───────────────────────┘
```

---

## Phase 4: Consistent Class Design (M4)

### 4.1 Naming Convention

| Level | Pattern | Example |
|-------|---------|---------|
| **PHP Namespace** | `GiftFlow\{Domain}` | `GiftFlow\Gateways\Stripe` |
| **PHP Class** | PascalCase | `StripeGateway`, `CampaignPostType` |
| **PHP Interface** | `{Name}Interface` | `GatewayInterface` |
| **PHP Abstract** | `Abstract{Name}` | `AbstractPostType` |
| **PHP Trait** | `{Name}Trait` | `HasSettingsTrait` |
| **Function** | `giftflow_{verb}_{noun}()` | `giftflow_get_campaign()` |
| **Hook** | `giftflow/{resource}.{action}` | `giftflow/donation.created` |
| **Meta Key** | `_giftflow_{key}` | `_giftflow_amount` |
| **Option Key** | `giftflow_{module}_options` | `giftflow_general_settings` |

### 4.2 Base Class Pattern

All domain classes extend a common base with shared utilities:

```php
namespace GiftFlow\Core;

abstract class AbstractModule {
    protected Container $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    abstract public function register(): void;
}
```

Every class implements a `register()` method that adds its hooks in one place — no implicit side-effects at `require` time.

### 4.3 Interface Segregation for Gateways

Split the monolithic gateway pattern into focused interfaces:

```php
interface GatewayInterface {
    public function get_id(): string;
    public function get_title(): string;
    public function is_enabled(): bool;
    public function get_description(): string;
}

interface PaymentProcessorInterface {
    public function process(array $data, int $donation_id): PaymentResult;
}

interface WebhookHandlerInterface {
    public function handle_webhook(): void;
}

interface SettingsProviderInterface {
    public function get_settings_fields(): array;
}
```

Each gateway implements only what it needs. BankTransfer implements `GatewayInterface` + `SettingsProviderInterface` but NOT `WebhookHandlerInterface`.

### 4.4 Gateways to Refactor

| Gateway | Current Lines | Target Files |
|---------|--------------|-------------|
| Stripe | 1677 lines | `Stripe/Gateway.php`, `Stripe/Client.php`, `Stripe/Webhook.php` |
| PayPal | 2957 lines | `PayPal/Gateway.php`, `PayPal/Client.php`, `PayPal/Webhook.php` |
| Bank Transfer | 279 lines | `BankTransfer/Gateway.php` |

---

## Phase 5: Developer Experience (M5)

### 5.1 Service Container & Filter-Based Extension

```php
// Third-party plugin extends GiftFlow
add_filter('giftflow.services', function(array $services) {
    $services[] = MyCustomGateway::class;
    return $services;
});

add_filter('giftflow.blocks', function(array $blocks) {
    $blocks[] = __DIR__ . '/blocks/my-custom-block';
    return $blocks;
});
```

### 5.2 Typed Everything

```php
class CampaignRepository {
    public function find(int $id): ?CampaignEntity;
    public function query(CampaignQuery $query): CampaignCollection;
    public function create(array $data): CampaignEntity;
    public function update(int $id, array $data): CampaignEntity;
}

class CampaignEntity {
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly int $goal_amount,     // cents
        public readonly ?DateTimeImmutable $start_date,
        public readonly ?DateTimeImmutable $end_date,
        public readonly string $currency,
        public readonly array $preset_amounts,
        public readonly bool $allow_custom_amount,
    ) {}
}
```

### 5.3 REST API with Schema

```php
class DonationController extends \WP_REST_Controller {
    public function register_routes(): void {
        register_rest_route('giftflow/v2', '/donations', [
            'methods'  => 'POST',
            'callback' => [$this, 'create'],
            'permission_callback' => '__return_true',
            'args' => [
                'campaign_id' => ['required' => true, 'type' => 'integer'],
                'amount'      => ['required' => true, 'type' => 'integer', 'minimum' => 100],
                'gateway'     => ['required' => true, 'type' => 'string', 'enum' => ['stripe', 'paypal', 'bank_transfer']],
            ],
        ]);
    }
}
```

### 5.4 Action/Filter Registry

Replace the thin `hooks.php` with a dedicated hook registry for discoverability:

```php
final class Hooks {
    public const DONATION_CREATED  = 'giftflow/donation.created';
    public const DONATION_COMPLETED = 'giftflow/donation.completed';
    public const CAMPAIGN_PUBLISHED = 'giftflow/campaign.published';
    // ...
}
```

---

## Phase 6: Build Pipeline (M6)

### 6.1 Replace Laravel Mix with `@wordpress/scripts`

```json
{
  "scripts": {
    "build": "wp-scripts build",
    "start": "wp-scripts start",
    "lint:js": "wp-scripts lint-js",
    "lint:css": "wp-scripts lint-style",
    "format": "wp-scripts format",
    "packages-update": "wp-scripts packages-update"
  }
}
```

### 6.2 Build Output

```
build/
├── blocks/
│   ├── donation-button/
│   │   ├── index.js          # block.json → editorScript auto-built
│   │   ├── view.js           # block.json → viewScript auto-built
│   │   └── style-index.css   # block.json → style auto-built
│   └── ...
├── settings/
│   └── index.js              # Settings page React app
└── assets/
    ├── admin.js
    └── admin.css
```

`wp-scripts` reads `block.json` and automatically builds scripts/styles — no webpack config needed.

---

## Phase 7: Migration & Rollout (M7)

### 7.1 Backwards Compatibility

| v1.0 | v2.0 Compatibility |
|------|-------------------|
| `[giftflow_donation_form]` | Continue supporting via `src/Shortcodes/` wrapper |
| `giftflow_get_options()` | Map to new SettingsRegistry in shim |
| `giftflow_load_files` filter | Replace with `giftflow.services` filter |
| Classic template files | Deprecation notice + fallback to FSE templates |
| `_amount` meta key | Keep, read/write via `DonationRepository` |

### 7.2 Database Migration

- Keep all existing post types, meta keys, and option names as-is.
- Add new settings option `giftflow_v2_settings` alongside old `giftflow_general_options`.
- Migration helper: auto-migrate old settings to new structure on first load.

### 7.3 Phased Rollout

| Milestone | Scope | Weeks |
|-----------|-------|-------|
| **M1** | ✅ PSR-4 autoload, new directory structure, Container, Plugin bootstrap | 1-2 |
| **M2** | Blocks migrated to block.json + Interactivity API | 2-3 |
| **M3** | Gutenberg-style settings page with inheritance | 2-3 |
| **M4** | Gateways refactored (interfaces, split PayPal) | 2 |
| **M5** | common.php split, REST API v2, Hooks registry | 1-2 |
| **M6** | Build pipeline (@wordpress/scripts), theme.json, i18n | 1-2 |
| **M7** | Documentation, developer guide, backward compat shims | 1 |

---

## Status Tracker

| Milestone | Status | Deliverables |
|-----------|--------|-------------|
| M1 — Foundation & Structure | ✅ Complete | PSR-4 autoload, Container, AbstractModule, Plugin |
| M2 — Block System Modernization | ✅ Complete | 8 block.json + render.php + style.css, theme.json, BlockRegistry |
| M3 — Gutenberg-Style Settings | ✅ Complete | SettingsRegistry (3-tier inheritance), SettingsController REST, React settings app |
| M4 — Consistent Class Design | ✅ Complete | GatewayInterface (4 interfaces), AbstractGateway, AbstractPostType, AbstractMetaBox, PaymentResult, HasSettingsTrait |
| M5 — Developer Experience | ✅ Complete | CurrencyService, EmailService, DashboardController, CampaignController, src/Functions/ domain split |
| M6 — Build Pipeline | ✅ Complete | @wordpress/scripts v30, webpack.config.js, build → build/ output |
| M7 — Migration & Rollout | ✅ Complete | Compat shims, giftflow.blocks filter, DEVELOPER_GUIDE.md, legacy migration |

---

## Final Deliverables Summary

### New Architecture (src/)

```
src/
├── Core/
│   ├── AbstractModule.php    — base for all modules
│   ├── AssetLoader.php       — dual-path (build/ + legacy) asset loading
│   ├── Compat.php            — backward compatibility layer
│   ├── Container.php         — PSR-11 service container
│   ├── HasSettingsTrait.php  — settings access for any class
│   ├── HookManager.php       — centralized hook constants
│   └── Plugin.php            — main bootstrap (replaces Loader)
├── Blocks/
│   └── BlockRegistry.php     — auto-discovery + giftflow.blocks filter
├── Gateways/
│   ├── AbstractGateway.php   — self-registering base class
│   ├── GatewayInterface.php  — 4 segregated interfaces
│   ├── GatewayRegistry.php   — gateway collection + query
│   └── PaymentResult.php     — immutable value object
├── PostTypes/
│   └── AbstractPostType.php  — typed CPT base
├── Meta/
│   └── AbstractMetaBox.php   — typed metabox base
├── Settings/
│   └── SettingsRegistry.php  — 3-tier inheritance (Global → Campaign → Block)
├── REST/
│   ├── SettingsController.php  — giftflow/v2/settings
│   ├── DashboardController.php — giftflow/v2/dashboard
│   └── CampaignController.php  — giftflow/v2/campaigns
├── Services/
│   ├── CurrencyService.php   — currency lookup + formatting
│   └── EmailService.php      — email templates + sending
└── Functions/
    ├── helpers.php            — utility wrappers
    ├── campaigns.php          — campaign wrappers
    └── templates.php          — template wrappers
```

### Block Structure (8 blocks)

Each block has:
```
blocks/{name}/
├── block.json    # register_block_type_from_metadata()
├── render.php    # Render callback with Interactivity API
├── block.js      # Editor JS component
└── style.css     # Block styles
```

### Settings (3-tier inheritance)

```
Global Defaults (giftflow_v2_settings)
  ↓ override
Campaign Post Meta (_giftflow_{key})
  ↓ override
Block Instance Attributes (__inherited_{key} = false)
```

### Build System

```
npm run build  →  wp-scripts build  →  build/
  build/admin.js + .asset.php
  build/settings.js + .asset.php
  build/frontend-common.js + .asset.php
  build/blocks/*.js + .asset.php  (8 blocks)
```

### Verification

- `composer lint` — 110/110 files, 0 errors
- `composer dump-autoload -o` — 2173 classes
- `wp-scripts build` — compiled successfully
- All 8 blocks registered via block.json
- Legacy API fully compatible (giftflow_load_files, giftflow_get_options, etc.)
