<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AIProvider;
use App\Services\AI\AIService;
use Exception;
use Flash;

class AIProviderController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display a listing of AI providers
     */
    public function index()
    {
        $providers = AIProvider::orderBy('is_default', 'desc')
            ->orderBy('is_active', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        $activeProvider = $this->aiService->getActiveProvider();
        $supportedProviders = AIProvider::getSupportedProviders();

        return view('admin.ai.providers.index', compact('providers', 'activeProvider', 'supportedProviders'));
    }

    /**
     * Show form for creating a new provider
     */
    public function create()
    {
        $supportedProviders = AIProvider::getSupportedProviders();
        return view('admin.ai.providers.create', compact('supportedProviders'));
    }

    /**
     * Store a newly created provider
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'provider_type' => 'required|string',
            'model' => 'required|string|max:100',
            'api_key' => 'required|string',
            'base_url' => 'nullable|url|max:255',
            'timeout_sec' => 'nullable|integer|min:5|max:120',
        ]);

        $provider = new AIProvider();
        $provider->name = $request->input('name');
        $provider->provider_type = $request->input('provider_type');
        $provider->model = $request->input('model');
        $provider->api_key = $request->input('api_key');
        $provider->base_url = $request->input('base_url');
        $provider->timeout_sec = $request->input('timeout_sec', 30);
        $provider->is_active = $request->has('is_active') ? 1 : 0;
        $provider->is_default = 0; // New provider never replaces active default automatically!
        $provider->status = $provider->is_active ? 'active' : 'inactive';
        $provider->save();

        flash('AI Provider "' . $provider->name . '" has been added successfully. It is saved as Inactive. You can test it and explicitly set it as Active when ready.')->success();
        return redirect()->route('admin.ai.providers');
    }

    /**
     * Show form for editing a provider
     */
    public function edit($id)
    {
        $provider = AIProvider::findOrFail($id);
        $supportedProviders = AIProvider::getSupportedProviders();
        return view('admin.ai.providers.edit', compact('provider', 'supportedProviders'));
    }

    /**
     * Update the specified provider
     */
    public function update(Request $request, $id)
    {
        $provider = AIProvider::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'provider_type' => 'required|string',
            'model' => 'required|string|max:100',
            'api_key' => 'nullable|string',
            'base_url' => 'nullable|url|max:255',
            'timeout_sec' => 'nullable|integer|min:5|max:120',
        ]);

        $provider->name = $request->input('name');
        $provider->provider_type = $request->input('provider_type');
        $provider->model = $request->input('model');

        // Only update API key if explicitly provided (preserve existing if blank)
        if ($request->filled('api_key')) {
            $provider->api_key = $request->input('api_key');
        }

        $provider->base_url = $request->input('base_url');
        $provider->timeout_sec = $request->input('timeout_sec', 30);
        $provider->is_active = $request->has('is_active') ? 1 : 0;

        if (!$provider->is_active && $provider->is_default) {
            $provider->is_default = 0;
        }

        $provider->save();

        flash('AI Provider "' . $provider->name . '" updated successfully.')->success();
        return redirect()->route('admin.ai.providers');
    }

    /**
     * Explicitly set this provider as the Active Default provider
     */
    public function setActive($id)
    {
        $provider = AIProvider::findOrFail($id);

        // Deactivate default flag on all others
        AIProvider::query()->update(['is_default' => 0]);

        $provider->is_active = 1;
        $provider->is_default = 1;
        $provider->status = 'active';
        $provider->save();

        flash('✓ Provider "' . $provider->name . '" (' . $provider->model . ') is now the PRIMARY ACTIVE AI Provider for the portal.')->success();
        return redirect()->route('admin.ai.providers');
    }

    /**
     * Toggle Enable / Disable status
     */
    public function toggleStatus($id)
    {
        $provider = AIProvider::findOrFail($id);
        $provider->is_active = !$provider->is_active;

        if (!$provider->is_active) {
            $provider->is_default = 0;
            $provider->status = 'inactive';
        } else {
            $provider->status = 'active';
        }

        $provider->save();

        $statusMsg = $provider->is_active ? 'Enabled' : 'Disabled';
        flash('Provider "' . $provider->name . '" has been ' . $statusMsg . '.')->info();
        return redirect()->route('admin.ai.providers');
    }

    /**
     * Test connection for an AI provider via AJAX
     */
    public function testConnection($id)
    {
        try {
            $provider = AIProvider::findOrFail($id);
            $result = $this->aiService->testProvider($provider);

            return response()->json([
                'success' => $result['success'],
                'response_time_ms' => $result['response_time_ms'] ?? 0,
                'message' => $result['message'] ?? '',
                'model' => $provider->model,
                'status' => $provider->status,
                'last_tested_at' => $provider->last_tested_at ? $provider->last_tested_at->format('M d, Y h:i A') : 'Just now',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'response_time_ms' => 0,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an AI provider
     */
    public function destroy($id)
    {
        $provider = AIProvider::findOrFail($id);
        $name = $provider->name;
        $provider->delete();

        flash('AI Provider "' . $name . '" was deleted.')->success();
        return redirect()->route('admin.ai.providers');
    }
}
