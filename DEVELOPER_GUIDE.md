# GiftFlow Developer Guide

## Quick Start

### Adding a Custom Block

```php
// 1. Create blocks/my-block/block.json
// 2. Register via filter:
add_filter('giftflow.blocks', function(array $blocks) {
    $blocks[] = plugin_dir_path(__FILE__) . 'blocks/my-block';
    return $blocks;
});
```

### Adding a Payment Gateway

```php
use GiftFlow\Gateways\AbstractGateway;
use GiftFlow\Gateways\PaymentProcessorInterface;

class MyGateway extends AbstractGateway implements PaymentProcessorInterface {
    protected function init(): void {
        $this->gateway_id          = 'mygateway';
        $this->gateway_title       = 'My Gateway';
        $this->gateway_description = 'Custom payment integration.';
    }

    public function render_form(array $data): string {
        return '<button>Pay with My Gateway</button>';
    }

    public function process_payment(array $data, int $donation_id): \GiftFlow\Gateways\PaymentResult {
        return \GiftFlow\Gateways\PaymentResult::success('txn_123');
    }

    public function get_settings_fields(): array {
        return [
            ['id' => 'api_key', 'label' => 'API Key', 'type' => 'text'],
        ];
    }
}

// Register via container:
add_filter('giftflow.services', function(array $services) {
    $services[MyGateway::class] = fn($c) => new MyGateway($c);
    return $services;
});
```

### Accessing Settings

```php
use GiftFlow\Settings\SettingsRegistry;

// Get global setting: $0.25 minimum
$min = $settings->get('min_amount');

// Get campaign override:
$currency = $settings->get('currency', $campaign_id);

// Get block-level override:
$color = $settings->get('progress_bar_color', $campaign_id, $block_attrs);

// Get all settings:
$all = $settings->get_all();
```

### Using the Container

```php
$container = GiftFlow\Core\Plugin::instance()->container();

$currency   = $container->get(\GiftFlow\Services\CurrencyService::class);
$formatted  = $currency->format_amount(2500); // "$25.00"

$gateways   = $container->get(\GiftFlow\Gateways\GatewayRegistry::class);
$stripe     = $gateways->get_gateway('stripe');
```

### REST API

```
GET  /wp-json/giftflow/v2/settings
POST /wp-json/giftflow/v2/settings
GET  /wp-json/giftflow/v2/settings/defaults
GET  /wp-json/giftflow/v2/campaigns?per_page=10
GET  /wp-json/giftflow/v2/dashboard/overview
GET  /wp-json/giftflow/v2/dashboard/charts?period=30d
GET  /wp-json/giftflow/v2/campaign/{id}/settings
POST /wp-json/giftflow/v2/campaign/{id}/settings
```

### Hook Reference

| Hook | Type | Description |
|------|------|-------------|
| `giftflow.services` | Filter | Register additional services in container |
| `giftflow.blocks` | Filter | Register third-party block directories |
| `giftflow.blocks_registered` | Action | Fires after all blocks are registered |
| `giftflow.gateways` | Filter | Filter registered gateway instances |
| `giftflow/donation.created` | Action | Fires when a donation is created |
| `giftflow/donation.completed` | Action | Fires when a donation is completed |
| `giftflow/campaign.published` | Action | Fires when a campaign is published |
| `giftflow/settings.save` | Filter | Filter settings before save |
| `giftflow_campaign_single_content_tabs` | Filter | Modify campaign content tabs |
| `giftflow_campaign_grid_query_args` | Filter | Modify campaign grid query args |
| `giftflow_post_type.registered` | Action | Fires after post type registration |

## Class Architecture

```
GiftFlow\Core\AbstractModule ← all domain classes
├── AssetLoader        — script/style enqueuing
├── Compat             — backward compatibility
├── BlockRegistry      — block auto-discovery
├── SettingsRegistry   — settings with inheritance
├── GatewayRegistry    — gateway collection
│
GiftFlow\Gateways\
├── GatewayInterface          — identity + render
├── PaymentProcessorInterface — process payment
├── WebhookHandlerInterface   — handle webhooks
├── SettingsProviderInterface — admin settings
├── AbstractGateway           — base implementation
├── PaymentResult             — immutable result value object
│
GiftFlow\PostTypes\AbstractPostType    — CPT base
GiftFlow\Meta\AbstractMetaBox          — meta box base
GiftFlow\Services\{Currency,Email}Service — shared services
GiftFlow\REST\{Settings,Dashboard,Campaign}Controller — REST endpoints
```
