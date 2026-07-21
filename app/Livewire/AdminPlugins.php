<?php

namespace App\Livewire;

use App\Models\Plugin;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Plugin Manager')]
class AdminPlugins extends Component
{
    public ?int $selectedPluginId = null;
    public array $settings = [];
    public string $activeTab = 'settings';
    public string $successMessage = '';
    public string $errorMessage = '';
    public string $filterType = '';
    public string $search = '';
    public string $activationKey = '';

    public function mount(): void
    {
        //
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getSelectedPluginProperty(): ?Plugin
    {
        return $this->selectedPluginId
            ? Plugin::find($this->selectedPluginId)
            : null;
    }

    public function getPluginsProperty()
    {
        return Plugin::orderBy('type')->orderBy('name')->get();
    }

    public function getFilteredPluginsProperty()
    {
        return $this->plugins->filter(function ($plugin) {
            $matchesSearch = empty($this->search)
                || str_contains(strtolower($plugin->name), strtolower($this->search))
                || str_contains(strtolower($plugin->shortcode ?? ''), strtolower($this->search));

            $matchesType = empty($this->filterType) || $plugin->type === $this->filterType;

            return $matchesSearch && $matchesType;
        });
    }

    public function getPluginTypesProperty()
    {
        return $this->plugins->pluck('type')->unique()->sort()->values();
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    public function selectPlugin(int $id): void
    {
        $plugin = Plugin::findOrFail($id);
        $this->selectedPluginId = $id;
        $this->settings = $plugin->getSettings();
        $this->activeTab = 'settings';
        $this->successMessage = '';
        $this->errorMessage = '';
        $this->activationKey = '';
    }

    public function closePanel(): void
    {
        $this->selectedPluginId = null;
        $this->settings = [];
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function saveSettings(): void
    {
        if (!$this->selectedPluginId) return;

        $plugin = Plugin::findOrFail($this->selectedPluginId);
        $plugin->saveSettings($this->settings);
        $this->successMessage = 'Settings saved successfully.';
        $this->errorMessage = '';

        // Reload settings
        $this->settings = $plugin->getSettings();
    }

    public function toggleActive(int $id): void
    {
        $plugin = Plugin::findOrFail($id);
        $plugin->activation_status = $plugin->activation_status ? 0 : 1;
        if ($plugin->activation_status) {
            $plugin->activation_date = now();
        }
        $plugin->save();
    }

    public function activatePlugin(): void
    {
        if (!$this->selectedPluginId) return;

        $plugin = Plugin::findOrFail($this->selectedPluginId);
        $plugin->activation_status = 1;
        $plugin->activation_date = now();
        $plugin->activation_key = $this->activationKey;
        $plugin->save();

        $this->successMessage = $plugin->activation_success_msg ?: 'Plugin activated successfully.';
    }

    public function deactivatePlugin(int $id): void
    {
        $plugin = Plugin::findOrFail($id);
        $plugin->activation_status = 0;
        $plugin->save();
    }

    public function render(): View
    {
        return view('livewire.admin-plugins', [
            'selectedPlugin'  => $this->selectedPlugin,
            'plugins'         => $this->plugins,
            'filteredPlugins' => $this->filteredPlugins,
            'pluginTypes'     => $this->pluginTypes,
        ]);
    }
}
