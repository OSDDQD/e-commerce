<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;

class Item extends Model
{
    use HasFactory,
        HasSlug,
        CrudTrait,
        HasTranslations;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'slug',
        'images',
        'position',
        'is_active',
        'is_digital',
    ];

    protected $translatable = ['name', 'description'];


    // Set options for image attributes
    const DISK = 'public';
    const FIELD = 'images';
    const PATH = 'items';

    protected $casts = [
        'images' => 'array'
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            if (count((array)$obj->{self::FIELD})) {
                foreach ($obj->{self::FIELD} as $file_path) {
                    \Storage::disk(self::DISK)->delete($file_path);
                }
            }
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function setImagesAttribute($value)
    {
        $this->uploadMultipleFilesToDisk(
            $value,
            self::FIELD,
            self::DISK,
            self::PATH
        );
    }
}
