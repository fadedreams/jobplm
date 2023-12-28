<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::get();
        return view('contact.index', compact('contacts'));

        // return $contact;
    }

    public function store()
    {
        Contact::create([
            'name' => 'david1',
        ]);

        return back();
    }
}
