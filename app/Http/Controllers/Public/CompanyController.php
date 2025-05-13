<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\VenueCategory;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {

        if ($request->has('venue_categories')) {
            return response([
                'companies' => Company::byVenueCategory($request->venue_categories)
                    ->where('searchable', true)
                    ->select('id')
                    ->with('translation')
                    ->get(),
            ], 200);
        }

        return response([
            'companies' => Company::select('id')
                ->with('translation')
                ->get(),
        ], 200);
    }

    public function formData()
    {
        return response([
            'venue_categories' => VenueCategory::select('id')
                ->with('translation:')
                ->get(),
        ], 200);
    }
}
