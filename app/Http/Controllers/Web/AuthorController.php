<?php

namespace App\Http\Controllers\Web;

use App\Models\Author;
use App\Support\Enums\MediaCollectionEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Http\Requests\AuthorRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthorController extends Controller
{
	/**
	 * Show all authors
	 */
	public function index(Request $request): Response|ResourceCollection
	{
		$authors = Author::with('books')
			->orderBy('first_name')
			->cursorPaginate(10);

		if ($request->wantsJson()) {
			return AuthorResource::collection($authors);
		}

		return Inertia::render('Author/Index', [
			'authors'	=> AuthorResource::collection($authors)
		]);
	}

	/**
	 * Render author data
	 */
	public function show(string $slug): Response
	{
		$author = Author::where('slug', $slug)
			->with('books')
			->firstOrFail();

		return Inertia::render('Author/Detail', [
			'author'		=> new AuthorResource($author),
			'bookCount'	=> $author->books->count(),
		]);
	}
	
	/**
	 * Handle request to store author
	 */
	public function store(AuthorRequest $request): RedirectResponse
	{
		$validatedData = $request->validated();
		$author = Author::create($validatedData);

		if ($portraitFile = $validatedData['portrait_file']) {
			$fileName = $author->slug;
			$author
				->addMedia($portraitFile)
				->usingName($fileName)
				->usingFileName("$fileName.{$portraitFile->extension()}")
				->toMediaCollection(MediaCollectionEnum::AUTHOR_PORTRAITS);
		}

		return back()->with(
			'alert',
			[
				'type'		=> 'success', // 'warning', 'danger'
				'message'	=> __('alerts.author_store_success'),
			]
		);
	}

	/**
	 * Update the author
	 */
	public function update(AuthorRequest $request, int $id): RedirectResponse
	{
		try {
			$author = Author::findOrFail($id);
			$validatedData = $request->validated();
			$author->update($validatedData);

			$slug = $author->slug;

			if ($portraitFile = $validatedData['portrait_file']) {
				if ($media = $author->getFirstMedia(MediaCollectionEnum::AUTHOR_PORTRAITS)) {
					$media->delete();
				}

				$author
					->addMedia($portraitFile)
					->usingName($slug)
					->usingFileName("$slug.{$portraitFile->extension()}")
					->toMediaCollection(MediaCollectionEnum::AUTHOR_PORTRAITS);
			}

			return redirect()->route('author.show', [$author])->with(
				'alert',
				[
					'type' => 'success',
					'message' => __('alerts.author_update_success'),
				]
			);
		} catch (\Exception $e) {
			return back()->with(
				'alert',
				[
					'type' => 'danger',
					'message' => __('alerts.author_update_danger'),
				]
			);
		}
	}

	/**
	 * Delete the author
	 */
	public function destroy(int $id): RedirectResponse
	{
		Author::destroy($id);

		return redirect()->route('author.index')->with(
			'alert',
			[
				'type'		=> 'success', // 'warning', 'danger'
				'message'	=> __('alerts.author_destroy_success'),
			]
		);
	}
}
