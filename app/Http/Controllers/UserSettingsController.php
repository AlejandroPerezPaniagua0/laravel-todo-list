<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    public function __construct() {}

    /**
     * Show the user settings page
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $settings = UserSetting::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'theme' => 'light',
                'language' => 'en',
                'email_notifications' => true,
                'timezone' => 'UTC',
                'date_format' => 'd/m/Y',
            ]
        );

        $themeOptions = UserSetting::getThemeOptions();
        $languageOptions = UserSetting::getLanguageOptions();
        $dateFormatOptions = UserSetting::getDateFormatOptions();
        $timezoneOptions = UserSetting::getTimezoneOptions();

        return view('configuration.index', compact(
            'settings',
            'themeOptions',
            'languageOptions',
            'dateFormatOptions',
            'timezoneOptions'
        ));
    }

    /**
     * Update the user settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
            'language' => 'required|in:es,en',
            'email_notifications' => 'nullable|boolean',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
        ]);
        $validated['email_notifications'] = $request->has('email_notifications');

        // Update or create the user settings
        UserSetting::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        // Set the new locale immediately
        app()->setLocale($validated['language']);

        return redirect()->back()->with('success', __('settings.updated_successfully'));
    }

    /**
     * Reset the user settings to the default values
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        $settings = UserSetting::where('user_id', Auth::id())->first();
        
        if ($settings) {
            $settings->update([
                'theme' => 'light',
                'language' => 'en',
                'email_notifications' => true,
                'timezone' => 'UTC',
                'date_format' => 'd/m/Y',
            ]);
        }

        return redirect()->back()->with('success', __('settings.restored_successfully'));
    }
}
