<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class CategoryController extends Controller
{

	public function index()
	{
		$categories = Category::with('books')->get();
		return Inertia::render('Index', ['categories' => $categories]);
	}

	/*
	public function show(int $id)
	{
		return Inertia::render(
			'Book/Index',
			[
				'book' => Book::findOrFail($id)
				'image' => $book->getFirstMedia('book-covers')->toHtml()
			]
		);
	}
	*/

	public function store(CategoryStoreRequest $request)
	{
		$validatedData = $request->validated();
    Category::create($validatedData);

		return back()->with(
			'alert',
			[
				'type' => 'success', // 'warning', 'danger'
				'message' => 'Category successfully created',
			]
		);
	}

	public function destroy(int $id): RedirectResponse
	{
		Category::destroy($id);

		return back()->with(
			'alert',
			[
				'type' => 'success', // 'warning', 'danger'
				'message' => 'Category successfully deleted',
			]
		);
	}
	
}
