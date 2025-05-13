<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\VenueCategoryRequest;
use App\Models\VenueCategory;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenueCategoryController extends Controller
{
    /**
     * Creates a new instance of the FileUploadService class.
     *
     * @param  Request  $request  The HTTP request object.
     * @return FileUploadService A new instance of the FileUploadService class.
     */
    private function fileUploadService(Request $request)
    {
        return new FileUploadService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venueCategories = VenueCategory::with('translation')->paginate(20);

        return response([
            'categories' => $venueCategories,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VenueCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $venueCategoryData = $request->only(['image']);
            if (! empty($venueCategoryData['image'])) {
                $venueCategoryData['image'] = $this->fileUploadService($request)->fileUpload($venueCategoryData['image'], VenueCategory::$imagePath)['path'];
            }

            $venueCategory = VenueCategory::create($venueCategoryData);
            $venueCategory->translations()->createMany($request->translations);

            DB::commit();

            return response([
                'category' => $venueCategory,
                'message' => __('category.category_created_successfully'),
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'message' => __('category.category_created_failed'),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VenueCategory $venueCategory)
    {
        $venueCategory->load('translations');

        return response([
            'category' => $venueCategory,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VenueCategoryRequest $request, VenueCategory $venueCategory)
    {
        DB::beginTransaction();
        try {
            $venueCategoryData = $request->only(['image']);
            if (! empty($venueCategoryData['image'])) {
                $venueCategoryData['image'] = $this->fileUploadService($request)->fileUpload($venueCategoryData['image'], VenueCategory::$imagePath)['path'];
            }

            $venueCategory->update($venueCategoryData);
            $venueCategory->translations()->delete();
            $venueCategory->translations()->createMany($request->translations);

            DB::commit();

            return response([
                'category' => $venueCategory,
                'message' => __('category.category_updated_successfully'),
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'message' => __('category.category_updated_failed'),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VenueCategory $venueCategory)
    {
        DB::beginTransaction();
        try {
            $venueCategory->delete();
            DB::commit();

            return response([
                'message' => __('category.category_deleted_successfully'),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'message' => __('category.category_deleted_failed'),
            ], 500);
        }
    }
}
