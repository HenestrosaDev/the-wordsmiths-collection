<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReview;
use App\Models\Category;
use App\Models\User;
use App\Support\Enums\MediaCollectionEnum;
use App\Support\Enums\MediaConversionEnum;
use App\Http\Requests\BookStoreRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BookController extends Controller
{

	public function index()
	{
		return Inertia::render(
			'Index', 
			[
				'books' => Book::query()
					->when(FacadesRequest::input('search'), function ($query, $search) {
						$query->where('name', 'like', "%{$search}%");
					})
					->withQueryString()
					->paginate(10)
					->through(fn ($book) => [
						'title' => $book->title,
						'slug' => Str::slug($book->title),
						'cover' => $book->getFirstMedia(MediaCollectionEnum::BOOK_COVERS)->toHtml(),
						// 'can' => [
						// 	'edit' => Auth::user()->can('edit', User::class)
						// ]  //TODO
					]),

				'filters' => FacadesRequest::only(['search']),
				// 'can' => [
				// 	'createUser' => Auth::user()->can('create', User::class)
				// ] //TODO
			]
		);
	}

	public function show(HttpRequest $request, string $slug)
	{
		$book = Book::where('slug', $slug)->firstOrFail();

		$cover = $book->getFirstMedia(MediaCollectionEnum::BOOK_COVERS);
		$responsiveCover = $cover(MediaConversionEnum::WEBP)->toHtml();

		$bookData = [
			'id' 					=> $book->id,
			'isbn' 				=> $book->isbn,
			'slug'				=> $book->slug,
			'title' 			=> $book->title,
			'description' => $book->description,
			'pageCount' 	=> $book->page_count,
			'language' 		=> $book->language,
			'year' 				=> $book->year,
			'is_premium' 	=> $book->is_premium,
			'cover' 			=> $responsiveCover,
			'authors'			=> $book->authors->map(function($author) {
				return [
					'name' => "$author->first_name $author->last_name",
					'slug' => $author->slug,
				];
			}),
			'categories' => $book->categories->map(function($category) {
				return [
					'name' => $category->name,
					'slug' => $category->slug,
				];
			}),
			'reviews' => $book->bookReviews->map(function($review) {
				return [
					'username' 		=> User::find($review->user_id)->username,
					'rating' 			=> $review->rating,
					'reviewText' 	=> $review->review_text,
					'date'				=> $review->created_at,
				];
			}),
    ];

		$relatedBooks = Category::find($book->categories[0]->id)
			->books
			->filter(function ($relatedBook) use ($book) {
				// Filter out the book with the same ID as $book
				return $relatedBook->id !== $book->id;
			})
			->map(function ($book) {
				$cover = $book->getFirstMedia(MediaCollectionEnum::BOOK_COVERS);
				$responsiveCover = $cover(MediaConversionEnum::WEBP)->toHtml();

				return [
					'id' 		=> $book->id,
					'title' => $book->title,
					'slug' 	=> Str::slug($book->title),
					'cover' => $responsiveCover,
				];
			})
			->take(18) // If there are fewer than 18 related books, it will return all of them.
			->values(); // https://stackoverflow.com/questions/59338079/can-not-return-a-collection-as-an-array-after-it-has-been-filtered

		$user = $request->user();
		$canPublishReview = false;

		if ($user) {
			if ($user->subscription !== null) {
				// subscription_plan_id === 2 => Basic plan
				if ($book->is_premium && $user->subscription->subscription_plan_id === 2) {
					$canPublishReview = false;
				} else {
					$doesReviewExist = BookReview::where('book_id', $book->id)
						->where('user_id', $user->id)
						->exists();

					$canPublishReview = !$doesReviewExist && $user->subscription->status === 'active';
				}
			}
		}

		return Inertia::render('Book/Detail', [
			'book' 							=> $bookData,
			'relatedBooks'			=> $relatedBooks,
			'canPublishReview'	=> $canPublishReview
		]);
	}

	public function read(string $slug)
	{
		$book = Book::where('slug', $slug)->firstOrFail();
		$bookFile = $book->getFirstMedia(MediaCollectionEnum::BOOKS);

		return Inertia::render('Book/Read', [
			'pdfUrl'		=> $bookFile->getFullUrl(),
			'bookTitle' => $book->title,
			'bookSlug' 	=> $book->slug,
		]);
	}

	public function store(BookStoreRequest $request)
	{
		$validatedData = $request->validated();
		$book = Book::create($validatedData);

		foreach ($validatedData['authors_id'] as $author_id)
		{
			$book->authors()->attach($author_id);
		}

		foreach ($validatedData['categories_id'] as $category_id)
		{
			$book->categories()->attach($category_id);
		}

		$book
			->addMedia($validatedData['book_file'])
			->toMediaCollection(MediaCollectionEnum::BOOKS);

		$titleSlug = Str::slug($book->title);
		$ext = $validatedData['cover_image']->extension();
		$fileName = "$titleSlug.$ext";

		$book
			->addMedia($validatedData['cover_image'])
			->usingName($titleSlug)
			->usingFileName($fileName)
			->toMediaCollection(MediaCollectionEnum::BOOK_COVERS);

		return back()->with(
			'alert',
			[
				'type' => 'success',
				'message' => 'Book successfully created',
			]
		);
	}

	public function destroy(int $id): RedirectResponse
	{
		Book::destroy($id);

		return back()->with(
			'alert',
			[
				'type' => 'success',
				'message' => 'Book successfully deleted',
			]
		);
	}

}
