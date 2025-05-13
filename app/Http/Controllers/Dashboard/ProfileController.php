<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ProfilePasswordRequest;
use App\Http\Requests\Dashboard\ProfileRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function user()
    {
        $user = auth()->user();

        return response([
            'user' => $user,
        ], 200);
    }

    public function updateData(ProfileRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        $user->update($data);

        return response([
            'message' => __('profile.profile_updated_successfully'),
            'user' => $user,
        ], 200);
    }

    public function updatePassword(ProfilePasswordRequest $request)
    {
        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => __('profile.current_password_incorrect')], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => __('profile.password_updated')], 200);
    }

    public function companies()
    {
        $companies = Company::whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('translation:id,company_id,name')->select(['id', 'logo'])->get();

        return response([
            'companies' => $companies,
        ], 200);
    }

    public function getCurrentCompany()
    {
        $company = Company::where('companies.id', auth()->user()->current_company_id)->getTranslationsByDefaultLanguageId()->first();

        if ($company == null) {
            return response([
                'message' => __('profile.current_company_not_found'),
            ], 500);
        }

        $company->load('country', 'defaultLanguage', 'users', 'languages');

        return response([
            'company' => $company,
        ], 200);
    }

    public function setCurrentCompany(Company $company)
    {
        try {
            $user = auth()->user();
            $user->update(['current_company_id' => $company->id]);

            $company->load('translations', 'country', 'defaultLanguage', 'users', 'languages');

            return response([
                'message' => __('profile.current_company_updated_successfully'),
                'company' => $company,
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => __('profile.current_company_updated_failed'),
            ], 500);
        }
    }
}
