<?php

namespace App\Models;

use App\Support\Enums\MediaCollectionEnum;
use App\Support\Enums\MediaConversionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Cviebrock\EloquentSluggable\Sluggable;

class Book extends Model implements HasMedia
{
  use HasFactory;
	use InteractsWithMedia;
	use Sluggable;

	protected $fillable = [
		'title',
		'isbn',
		'description',
		'page_count',
		'language',
		'year',
		'is_premium',
	];

	/**
	 * Return the sluggable configuration array for this model.
	 * Part of Cviebrock\EloquentSluggable Composer package.
	 *
	 * @return array
	 */
	public function sluggable(): array
	{
		return [
			'slug' => [
				'source' => 'title',
				'onUpdate' => true,
			]
		];
	}

	/**
	 * Get the route key for the model.
	 * https://laravel.com/docs/10.x/routing#customizing-the-default-key-name
	 */
	public function getRouteKeyName(): string
	{
		return 'slug';
	}

	/**
	 * Define a many-to-many relationship with the Genre model.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function authors(): BelongsToMany
	{
		return $this->belongsToMany(Author::class);
	}

	/**
	 * Define a many-to-many relationship with the Genre model.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class);
	}

	/**
	 * Define a one-to-many relationship with the BookReview model.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function bookReviews(): HasMany
	{
		return $this->hasMany(BookReview::class);
	}

	/**
	 * Define a one-to-many relationship with the Bookmark model.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function bookmarks(): HasMany
	{
		return $this->hasMany(Bookmark::class);
	}

	public function registerMediaCollections(): void
	{
		$this
			->addMediaCollection(MediaCollectionEnum::BOOK_FILES)
			->acceptsMimeTypes(['application/pdf'])
			->singleFile();

		$this
			->addMediaCollection(MediaCollectionEnum::BOOK_COVERS)
			->acceptsMimeTypes(['image/png', 'image/jpeg', 'image/webp'])
			->singleFile();
	}

	public function registerMediaConversions(Media $media = null): void
	{
		$this
			->addMediaConversion(MediaConversionEnum::WEBP)
			->format('webp')
			->performOnCollections(MediaCollectionEnum::BOOK_COVERS)
			->withResponsiveImages()
			->nonQueued();
	}
}
