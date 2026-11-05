<?php

namespace App\Http\Controllers;

use App\Services\Payments\PaymentProcessorManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dispatches incoming webhook requests at POST /webhooks/payment/{provider}
 * to the matching auto-discovered PaymentPluginInterface driver.
 */
class PaymentPluginWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentProcessorManager $processorManager
    ) {}

    public function handle(Request $request, string $provider): Response
    {
        $plugin = $this->processorManager->resolveByTypeSlug($provider);

        if (!$plugin) {
            return response("Payment plugin '{$provider}' is not registered.", 404);
        }

        return $plugin->handleWebhook($request);
    }
}
