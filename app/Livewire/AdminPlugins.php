<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\Plugin;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

use Livewire\Attributes\Url;

#[Layout('layouts.app')]
#[Title('Plugin Manager')]
class AdminPlugins extends Component
{
    public ?int $selectedPluginId = null;
    public array $settings = [];
    public string $activeTab = 'settings';
    public string $successMessage = '';
    public string $errorMessage = '';

    // ── Translation tab state ─────────────────────────────────────────────────
    /** ID of the language currently being edited in the Translations tab. */
    public int $tlLangId = 0;
    /** Buffer of translated field values keyed by field_name. */
    public array $tlSettings = [];

    #[Url(as: 'type')]
    public string $filterType = '';
    public string $search = '';
    public string $activationKey = '';

    public function mount(): void
    {
        if (request()->has('type')) {
            $this->filterType = (string) request('type');
        }
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

            $matchesType = empty($this->filterType) || strtolower($plugin->type) === strtolower($this->filterType);

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
        // Toggle: clicking the already-open plugin closes the panel.
        if ($this->selectedPluginId === $id) {
            $this->closePanel();
            return;
        }

        $plugin = Plugin::findOrFail($id);
        $this->selectedPluginId = $id;
        $this->settings = $plugin->getSettings();
        $this->activeTab = 'settings';
        $this->successMessage = '';
        $this->errorMessage = '';
        $this->activationKey = '';
        // Reset translation tab state when switching plugins
        $this->tlLangId   = 0;
        $this->tlSettings = [];
    }

    public function closePanel(): void
    {
        $this->selectedPluginId = null;
        $this->settings = [];
        $this->successMessage = '';
        $this->errorMessage = '';
        $this->tlLangId   = 0;
        $this->tlSettings = [];
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;

        // Auto-select the first non-default language when opening the Translations tab
        if ($tab === 'translations' && $this->tlLangId === 0 && $this->selectedPluginId) {
            $firstLang = Language::where('is_default', false)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->first();
            if ($firstLang) {
                $this->selectTlLang($firstLang->id);
            }
        }
    }

    /**
     * Set the active translation language and load any existing translations
     * for the currently selected plugin from plugin_setting_translations.
     */
    public function selectTlLang(int $langId): void
    {
        $this->tlLangId = $langId;
        $this->tlSettings = [];

        if (!$this->selectedPluginId) {
            return;
        }

        $plugin = Plugin::findOrFail($this->selectedPluginId);
        $this->tlSettings = $plugin->settingTranslations()
            ->where('language_id', $langId)
            ->pluck('field_value', 'field_name')
            ->toArray();
    }

    /**
     * Save the currently buffered translation values for the selected plugin
     * and language into plugin_setting_translations.
     */
    public function saveTlSettings(): void
    {
        if (!$this->selectedPluginId || !$this->tlLangId) {
            return;
        }

        $plugin = Plugin::findOrFail($this->selectedPluginId);
        $plugin->saveSettingsForLanguage($this->tlLangId, $this->tlSettings);

        $this->successMessage = 'Translations saved successfully.';
        $this->errorMessage   = '';
    }

    /**
     * Trigger an OpenAI call to re-translate the selected plugin's fields for the selected language.
     */
    public function autoTranslatePlugin(): void
    {
        if (!$this->selectedPluginId || !$this->tlLangId) {
            return;
        }

        $plugin = Plugin::findOrFail($this->selectedPluginId);
        $lang = Language::find($this->tlLangId);
        if (!$lang) {
            return;
        }

        $service = app(\App\Services\TranslationService::class);
        try {
            $service->translatePlugin($plugin, $lang);
            $this->selectTlLang($this->tlLangId);
            $this->successMessage = 'Plugin translated using OpenAI for ' . $lang->name . '.';
            $this->errorMessage = '';
        } catch (\Throwable $e) {
            $this->errorMessage = 'AI Translation failed: ' . $e->getMessage();
        }
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
