<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CompanyRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Models\Company;
use App\Models\Country;
use App\Models\Language;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    /**
     * Constant to map form request data without step prefixes.
     */
    private const COMPANY_FIELDS = [
        'country_id' => 'step1.country_id',
        'default_language_id' => 'step1.default_language_id',
        'logo' => 'step1.logo',
        'email' => 'step1.email',
        'phone' => 'step1.phone',
        'zip' => 'step1.zip',
        'can_edit' => 'step1.can_edit',
        'searchable' => 'step1.searchable',
        'sub_domain' => 'step1.sub_domain',
        'latitude' => 'step3.latitude',
        'longitude' => 'step3.longitude',
    ];

    private const COMPANY_DETAILS_FIELDS = [
        'facebook' => 'step3.facebook',
        'twitter' => 'step3.twitter',
        'instagram' => 'step3.instagram',
        'linkedIn' => 'step3.linkedIn',
        'tiktok' => 'step3.tiktok',
    ];

    private const COMPANY_TRANSLATIONS_FIELDS = [
        'translations' => 'step2.translations',
        'languages' => 'step1.languages',
    ];

    /**
     * Creates a new instance of the FileUploadService class.
     *
     * @param  Request  $request  The HTTP request object.
     * @return FileUploadService A new instance of the FileUploadService class.
     */
    private function fileUploadService()
    {
        return new FileUploadService();
    }

    /**
     * Extracts the company data from the request based on the provided fields array.

     * @param  \Illuminate\Http\Request  $request  The incoming request object containing form data.
     * @param  array  $fields  An array of field names to extract data from the request.
     *
     * @return array  An associative array of extracted data, including uploaded files and regular inputs.
     */
    private function extractCompanyData(Request $request, array $fields)
    {
        return collect($fields)->mapWithKeys(function ($field, $key) use ($request) {
            if (strpos($field, 'logo') !== false && $field !== null) {
                return [$key => $request->file($field)];
            }

            return [$key => $request->input($field)];
        })->toArray();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function index()
    {
        Gate::authorize('viewAny', Company::class);

        $companies = Company::byUser(auth()->user())->getTranslationsByDefaultLanguageId()->get();

        return response([
            'companies' => $companies,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function store(CompanyRequest $request)
    {
        Gate::authorize('create', Company::class);

        DB::beginTransaction();
        try {
            $user = auth()->user();

            // Extract company data
            $companyData = $this->extractCompanyData($request, self::COMPANY_FIELDS);
            if (isset($companyData['logo']) && $companyData['logo'] !== null) {
                $companyData['logo'] = $this->fileUploadService()->fileUpload($companyData['logo'], Company::$logoPath, Company::getSizes())['path'];
            }

            $company = Company::create($companyData);

            $company->details()->create($this->extractCompanyData($request, self::COMPANY_DETAILS_FIELDS));

            $company->translations()->createMany($request->input(self::COMPANY_TRANSLATIONS_FIELDS['translations']));
            $company->languages()->attach($request->input(self::COMPANY_TRANSLATIONS_FIELDS['languages']));

            $company->users()->attach($user->id);

            // Attach ambassador if available
            if (!empty($user->ambassador_id)) {
                $ambassador = User::find($user->ambassador_id);
                $company->ambassadors()->attach($ambassador->id, ['can_edit' => $request->input('step1.can_edit')]);
            }

            $user->update(['current_company_id' => $company->id]);

            DB::commit();

            return response([
                'company' => $company,
                'message' => __('company.company_created_successfully'),
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                'message' => __('company.company_created_failed'),
                'error' => $th->getMessage(), // Optional: for debugging
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        Gate::authorize('view', $company);

        $company->load('country', 'defaultLanguage', 'details', 'translations', 'languages',);

        return response([
            'company' => new CompanyResource($company),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        Gate::authorize('update', $company);

        DB::beginTransaction();
        try {
            $user = auth()->user();

            $companyData = $this->extractCompanyData($request, self::COMPANY_FIELDS);
            if (! empty($company->logo)) {
                $this->fileUploadService()->deleteFile($company->logo, Company::getSizes());
            }

            $companyData['logo'] = isset($companyData['logo']) && $companyData['logo'] !== null
                ? $this->fileUploadService()->fileUpload($companyData['logo'], Company::$logoPath, Company::getSizes())['path']
                : null;

            $company->update($companyData);
            $company->details()->update($this->extractCompanyData($request, self::COMPANY_DETAILS_FIELDS));
            $company->translations()->delete();
            $company->translations()->createMany($request->input(self::COMPANY_TRANSLATIONS_FIELDS['translations']));
            $company->languages()->sync($request->input(self::COMPANY_TRANSLATIONS_FIELDS['languages']));

            // Update ambassador if available
            if (!empty($user->ambassador_id)) {
                $ambassador = User::find($user->ambassador_id);
                $company->ambassadors()->updateExistingPivot($ambassador->id, ['can_edit' => $request->input('step1.can_edit')]);
            }

            DB::commit();

            return response([
                'company' => $company,
                'message' => __('company.company_updated_successfully'),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                'message' => __('company.company_updated_failed'),
                'error' => $th->getMessage(), // Optional: for debugging
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        Gate::authorize('delete', $company);

        try {
            if (! empty($company->logo)) {
                $this->fileUploadService()->deleteFile($company->logo, Company::getSizes());
            }
            $company->delete();

            return response([
                'message' => __('company.company_deleted_successfully'),
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => __('company.company_deleted_failed'),
            ], 500);
        }
    }

    public function formData()
    {
        return response([
            'languages' => Language::select('id', 'name')->get(),
            'countries' => Country::select('id', 'name')->get(),
            'ambassador' => User::where('id', auth()->user()->ambassador_id)
                ->select('name', 'surname', 'email')
                ->first(),
        ], 200);
    }
}
