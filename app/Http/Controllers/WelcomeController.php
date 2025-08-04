<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function  index(Request $request)
    {
        $record = Record::with('book')
            ->latest()->paginate(10);

        $search_result = null;
        if ($request->filled('search')) {
            $search_term = $request->search;
            $filter = $request->filter ?? 'All';
            $query = Record::query()
                ->where(function($query) use ($search_term) {
                    $query->where('accession_number', 'LIKE', '%' . $search_term . '%')
                        ->orWhere('title', 'LIKE', '%' . $search_term . '%');
                });
            if ($filter !== 'All') {
                if ($filter === 'Books') {
                    $query->whereHas('book');
                } elseif ($filter === 'Electronic Collection') {
                    $query->whereHas('digitalResource');
                } elseif ($filter === 'Periodical Magazine') {
                    $query->whereHas('periodical');
                } elseif ($filter === 'Thesis and Dissertation') {
                    $query->whereHas('thesis');
                }
            }
            $search_result = $query->with('book')->limit(5)->get();
        }

        return Inertia::render('Welcome', [
            'records' => $record,
            'search_result' => $search_result,
            'search_term' => $request->search,
        ]);
    }

}
