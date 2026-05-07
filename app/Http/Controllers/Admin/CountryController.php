<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
            'code' => 'required|string|max:10|unique:countries,code',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Country::create($validated);

        return back()->with('success', 'Country terminal added to global registry.');
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'code' => 'required|string|max:10|unique:countries,code,' . $country->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $country->update($validated);

        return back()->with('success', 'Country registry updated.');
    }

    public function destroy(Country $country)
    {
        if ($country->conferences()->exists()) {
            return back()->with('error', 'Cannot neutralize country. Active events are still registered in this territory.');
        }

        $country->delete();
        return back()->with('success', 'Territory purged from registry.');
    }
}
