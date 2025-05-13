<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CategoryRequest;
use App\Http\Requests\Dashboard\CategorySortRequest;
use App\Models\Category;
use App\Models\Language;
use App\Models\Tag;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
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
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::denies('viewAny', Category::class)) {
            return response([
                'message' => __('policy.category_index'),
            ], 403);
        }

        $categories = Category::byCompany()->GetTranslationsByCompanyDefaultLanguageId()->paginate(20);

        return response([
            'categories' => $categories,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        if (Gate::denies('create', Category::class)) {
            return response([
                'message' => __('policy.category_create'),
            ], 403);
        }


            $categoryData = $request->only(['image', 'tags']);
            if (! empty($categoryData['image'])) {
                $categoryData['image'] = $this->fileUploadService()->fileUpload($categoryData['image'], Category::$imagePath, Category::getSizes())['path'];
            }
            $categoryData['sort'] = Category::byCompany()->count();

            $category = Category::create($categoryData);
            $category->translations()->createMany($request->translations);
            $category->tags()->attach($categoryData['tags']);

            DB::commit();

            return response([
                'category' => $category,
                'message' => __('category.category_created_successfully'),
            ], 201);
        DB::beginTransaction();
        try {
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
    public function show(Category $category)
    {
        if (Gate::denies('view', $category)) {
            return response([
                'message' => __('policy.category_show'),
            ], 403);
        }

        $category->load('translations', 'tags.translations');
        $category->tags->makeHidden('pivot');

        return response([
            'category' => $category,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        if (Gate::denies('update', $category)) {
            return response([
                'message' => __('policy.category_update'),
            ], 403);
        }

        DB::beginTransaction();
        try {
            $categoryData = $request->only(['image', 'tags']);
            if (! empty($category->image)) {
                $this->fileUploadService()->deleteFile($category->image, Category::getSizes());
            }

            $categoryData['image'] = isset($categoryData['image']) && $categoryData['image'] !== null
                ? $this->fileUploadService()->fileUpload($categoryData['image'], Category::$imagePath, Category::getSizes())['path']
                : null;

            $category->update($categoryData);
            $category->translations()->delete();
            $category->translations()->createMany($request->translations);
            $category->tags()->sync($categoryData['tags']);

            DB::commit();

            return response([
                'category' => $category,
                'message' => __('category.category_updated_successfully'),
            ], 200);
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
    public function destroy(Category $category)
    {
        if (Gate::denies('delete', $category)) {
            return response([
                'message' => __('policy.category_delete'),
            ], 403);
        }

        DB::beginTransaction();
        try {
            if (! empty($category->image)) {
                $this->fileUploadService()->deleteFile($category->image, Category::getSizes());
            }
            $category->delete();
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

    /**
     * Update the sort order of the specified resource in storage.
     */
    public function sort(CategorySortRequest $request)
    {
        DB::beginTransaction();
        try {
            $i = 0;
            foreach ($request->categories as $categoryId) {
                Category::find($categoryId)->update(['sort' => $i]);
                $i++;
            }
            DB::commit();

            return response([
                'message' => __('category.category_sort_successfully'),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'message' => __('category.category_sort_failed'),
            ], 500);
        }
    }

    public function formData()
    {
        $company_id = auth()->user()->current_company_id;

        return response([
            'tags' => Tag::select('id', 'icon', 'color')
                            ->GetTranslationsByCompanyDefaultLanguageId()
                            ->where('company_id', $company_id)->get(),

            'languages' => Language::select('id', 'name')->get(),
        ], 200);
    }
}
