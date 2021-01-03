<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Intervention\Image\ImageManagerStatic;

class Item extends Model
{
    use HasFactory, HasSlug, CrudTrait, HasTranslations;

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
        'images',
        'slug',
        'is_active',
        'is_digital',
        'show_in_menu'
    ];

    protected $translatable = ['name', 'description'];

    // Set options for image attributes
    const PATH = 'items';
    const EXT = 'png';
    const DISK = 'public';
    const FIELD = 'images';

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            \Storage::disk(self::DISK)->delete($obj->{self::FIELD});
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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scopeFirstLevelItems($query)
    {
        return $query->where('depth', '1')
            ->orWhere('depth', null)
            ->orderBy('lft', 'ASC');
    }

    /**
     * Store image attribute
     */
    public function setImageAttribute($value)
    {
        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            \Storage::disk(self::DISK)->delete($this->{self::FIELD});

            // set null in the database column
            $this->attributes[self::FIELD] = null;
        }

        // if a base64 was sent, store it in the db
        if (\Str::startsWith($value, 'data:image')) {
            // 0. Make the image
            $image = ImageManagerStatic::make($value)->encode(self::EXT, 90);

            // 1. Generate a filename.
            $filename = md5($value . time()) . '.' . self::EXT;

            // 2. Store the image on disk.
            \Storage::disk(self::DISK)->put(self::PATH . '/' . $filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            \Storage::disk(self::DISK)->delete($this->{self::FIELD});

            // 4. Save the public path to the database
            $this->attributes[self::FIELD] = self::PATH . '/' . $filename;
        }
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? \Storage::disk(self::DISK)->url($this->image)
            : false;
    }
}
