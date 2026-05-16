<?php

namespace App\Providers;

use AllowDynamicProperties;
use App\Models\Setting;
use http\Env;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{

    protected array $setting;

    /**
     * Register services.
     *
     *
     */

    public function register(): void
    {
        //
        // Register the settings singleton
        $this->app->singleton('settings', function () {

            return $this;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            // Check if database is connected
            DB::connection()->getPdo();
            // Load settings and cache them
            if (Schema::hasTable('settings')) {
                $this->loadSettings();
                $this->setAppNameFromSettings();
                $this->setTimezoneFromSettings();
                $this->setEmailSupportFromSettings();
            }
        } catch (\Exception $e) {
            // Log or handle the exception as needed
        }

    }

    public function getValue($type, $key)
    {
        $filtered = collect($this->settings[$type] ?? [])
            ->where('key', $key)
            ->first();
        return $filtered['value'] ?? null;
    }

    private function loadSettings(): void
    {
        $this->settings = Setting::all()->groupBy('type')->toArray();
        cache()->rememberForever('settings', function () {
            return $this->settings;
        });
    }

    private function setAppNameFromSettings(): void
    {
        $appName = $this->getValue('general', 'site_name');
        if ($appName) {
            Config::set('app.name', $appName);
            env('APP_NAME', $appName);
        }
    }

    private function setTimezoneFromSettings(): void
    {
        $timezone = $this->getValue('general', 'timezone');
        if ($timezone) {
            env('APP_TIMEZONE', $timezone);
            Config::set('app.timezone', $timezone);
        }
    }

    private function setEmailSupportFromSettings(): void
    {
        $email_setting = $this->getValue('general', 'support_email');
        if ($email_setting) {
            env('MAIL_FROM_ADDRESS', $email_setting);
            Config::set('mail.from.address', $email_setting);
        }
    }


}
