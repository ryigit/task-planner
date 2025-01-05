<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(): View|Factory|Application
    {
        $providers = Provider::all();
        return view('providers.index', compact('providers'));
    }

    public function create(): View|Factory|Application
    {
        return view('providers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|unique:providers|max:255',
            'type' => 'required|in:default,custom',
            'endpoint_url' => 'required|url',
            'field_mappings' => 'required_if:type,default|json',
            'is_active' => 'boolean'
        ]);

        if ($request->type === 'default') {
            $validated['field_mappings'] = $request->field_mappings;
        }

        Provider::create($validated);
        return redirect()->route('providers.index')->with('success', 'Provider created successfully');
    }

    public function edit(Provider $provider): View|Factory|Application
    {
        return view('providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:providers,name,' . $provider->id,
            'type' => 'required|in:default,custom',
            'endpoint_url' => 'required|url',
            'field_mappings' => 'required_if:type,default|json',
            'is_active' => 'boolean'
        ]);

        if ($request->type === 'default') {
            $validated['field_mappings'] = $request->field_mappings;
        }

        $provider->update($validated);
        return redirect()->route('providers.index')->with('success', 'Provider updated successfully');
    }

    public function destroy(Provider $provider): RedirectResponse
    {
        $provider->delete();
        return redirect()->route('providers.index')->with('success', 'Provider deleted successfully');
    }
}
