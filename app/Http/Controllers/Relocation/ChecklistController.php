<?php

namespace App\Http\Controllers\Relocation;

use App\Http\Controllers\Controller;

class ChecklistController extends Controller
{
    public function index()
    {
        return view('pages.relocation.checklist');
    }
}
