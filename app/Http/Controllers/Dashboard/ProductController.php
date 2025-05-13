<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ProductRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Product;
use App\Models\Tag;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
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
        if (Gate::denies('viewAny', Product::class)) {
            return response([
                'message' => __('policy.product_index'),
            ], 403);
        }

        $products = Product::byCompany()->getTranslationsByCompanyDefaultLanguageId()->with('categories')->paginate(20);

        return response([
            'products' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        if (Gate::denies('create', Product::class)) {
            return response([
                'message' => __('policy.product_create'),
            ], 403);
        }

        DB::beginTransaction();
        try {
            $productData = $request->only(['image', 'price', 'old_price', 'tags', 'categories']);

            if (! empty($productData['image'])) {
                $productData['image'] = $this->fileUploadService()->fileUpload($productData['image'], Product::$imagePath, Product::getSizes())['path'];
            }

            $product = Product::create($productData);
            $product->categories()->attach($productData['categories']);
            $product->translations()->createMany($request->translations);
            $product->tags()->attach($productData['tags']);

            DB::commit();

            return response([
                'product' => $product,
                'message' => __('product.product_created_successfully'),
            ]);

        } catch (\Exception $th) {
            DB::rollBack();

            return response([
                'message' => __('product.product_created_failed'),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (Gate::denies('view', $product)) {
            return response([
                'message' => __('policy.product_show'),
            ], 403);
        }
        $product->load('translations', 'categories.translations', 'tags.translations');
        $product->categories->makeHidden('pivot');
        $product->tags->makeHidden('pivot');

        return response([
            'product' => $product,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        if (Gate::denies('update', $product)) {
            return response([
                'message' => __('policy.product_update'),
            ], 403);
        }

        DB::beginTransaction();
        try {
            $productData = $request->only(['image', 'price', 'old_price', 'categories', 'tags']);

            if (! empty($product->image)) {
                $this->fileUploadService()->deleteFile($product->image, Product::getSizes());
            }

            $productData['image'] = isset($productData['image']) && $productData['image'] !== null
                ? $this->fileUploadService()->fileUpload($productData['image'], Product::$imagePath, Product::getSizes())['path']
                : null;

            $product->update($productData);
            $product->categories()->sync($productData['categories']);
            $product->translations()->delete();
            $product->translations()->createMany($request->translations);
            $product->tags()->sync($productData['tags']);

            DB::commit();

            return response([
                'product' => $product,
                'message' => __('product.product_updated_successfully'),
            ]);

        } catch (\Exception $th) {
            DB::rollBack();

            return response([
                'message' => __('product.product_updated_failed'),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (Gate::denies('delete', $product)) {
            return response([
                'message' => __('policy.product_delete'),
            ], 403);
        }

        try {
            if (! empty($product->image)) {
                $this->fileUploadService()->deleteFile($product->image, Product::getSizes());
            }

            $product->delete();

            return response([
                'message' => __('product.product_deleted_successfully'),
            ], 200);
        } catch (\Exception $th) {
            return response([
                'message' => __('product.product_deleted_failed'),
            ], 500);
        }
    }

    public function formData()
    {
        $company_id = auth()->user()->current_company_id;

        return response([
            'languages' => Language::select('id', 'name')->get(),
            'tags' => Tag::where('company_id', $company_id)
                            ->select('id', 'icon', 'color')
                            ->GetTranslationsByCompanyDefaultLanguageId()
                            ->get(),

            'categories' => Category::where('company_id', $company_id)
                                        ->select('id', 'company_id', 'sort')
                                        ->GetTranslationsByCompanyDefaultLanguageId()
                                        ->orderBy('sort')
                                        ->get(),

            'countries' => Country::select('id', 'name', 'currency', 'currency_symbol')->get()
        ], 200);
    }
}
