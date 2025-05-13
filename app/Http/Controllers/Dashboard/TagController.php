<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\TagRequest;
use App\Models\Language;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::denies('viewAny', Tag::class)) {
            return response([
                'message' => __('policy.tag_index'),
            ], 403);
        }

        $tags = Tag::byUser(auth()->user())->GetTranslationsByCompanyDefaultLanguageId()->paginate(20);

        return response([
            'tags' => $tags,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        if (Gate::denies('create', Tag::class)) {
            return response([
                'message' => __('policy.tag_create'),
            ], 403);
        }

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $tagData = $request->validated();

            $tag = Tag::create($tagData);
            $tag->translations()->createMany($request->translations);
            $tag->languages()->attach($request->languages);
            $tag->users()->attach($user->id);

            DB::commit();

            return response([
                'tag' => $tag,
                'message' => __('tag.tag_created_successfully'),
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'message' => __('tag.tag_created_failed'),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        if (Gate::denies('view', $tag)) {
            return response([
                'message' => __('policy.tag_show'),
            ], 403);
        }

        $tag->load('translations', 'languages');

        return response([
            'tag' => $tag,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, Tag $tag)
    {
        if (Gate::denies('update', $tag)) {
            return response([
                'message' => __('policy.tag_update'),
            ], 403);
        }

        DB::beginTransaction();
        try {
            $tagData = $request->validated();

            $tag->update($tagData);
            $tag->translations()->delete();
            $tag->translations()->createMany($request->translations);
            $tag->languages()->sync($request->languages);

            DB::commit();

            return response([
                'tag' => $tag,
                'message' => __('tag.tag_updated_successfully'),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'message' => __('tag.tag_updated_failed'),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        if (Gate::denies('delete', $tag)) {
            return response([
                'message' => __('policy.tag_delete'),
            ], 403);
        }

        try {
            $tag->delete();

            return response([
                'message' => __('tag.tag_deleted_successfully'),
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => __('tag.tag_deleted_failed'),
            ], 500);
        }
    }

    public function formData()
    {
        return response([
            'languages' => Language::select('id', 'name')->get(),
        ]);
    }
}
