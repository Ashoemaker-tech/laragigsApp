<?php

namespace App\Http\Controllers;

use App\Models\listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // Get all listings
    public function index() {
        return view('listings.index',[
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
            ]);
    }

    // Get a listing
    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);

    }

    // Show create form
    public function create() {
        return view('listings.create');
    }

    // Store a listing
    public function store(Request $request) {
        $formFields = $request->validate([
            'title' =>'required|max:255',
            'company' => ['required', Rule::unique('listings', 'company')],
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' =>'required',
            'location' =>'required',
        ]);

        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');

        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listed created successfully');


    }

    // Show Edit View
    public function edit(Listing $listing) {
        return view('listings.edit', [
            'listing' => $listing
        ]);

    }

    // Update a listing
    public function update(Request $request, Listing $listing) {

        // Ensure loggedin user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized action');
        }


        $formFields = $request->validate([
            'title' =>'required|max:255',
            'company' => 'required', 
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' =>'required',
            'location' =>'required',
        ]);

        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');

        }

        $listing->update($formFields);

        return back()->with('message', 'Listed updated successfully');

    }

    // Delete listing
    public function destroy(Listing $listing) {
        // Ensure loggedin user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized action');
        }
        
        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully');

    }

    // Manage listings
    public function manage() {
        return view('listings.manage', ['listings' => auth()->user()->listing()->get()]);

    }
}
