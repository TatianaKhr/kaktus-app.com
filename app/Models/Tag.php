<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'tags';
    protected $guarded = false;

    public function dishes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Dish::class,'dishes_tags', 'tag_id', 'dish_id');
    }
}
