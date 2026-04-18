<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->{$model->getSlugSourceColumn()});
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->getSlugSourceColumn()) && !$model->isDirty('slug')) {
                $model->slug = $model->generateUniqueSlug($model->{$model->getSlugSourceColumn()});
            }
        });
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

    /**
     * Get the column name to use as the slug source.
     *
     * @return string
     */
    protected function getSlugSourceColumn()
    {
        return 'name';
    }

    /**
     * Generate a unique slug.
     *
     * @param string $value
     * @return string
     */
    protected function generateUniqueSlug($value)
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($this->exists, function ($query) {
                return $query->where($this->getKeyName(), '!=', $this->getKey());
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
