<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Product[] $products
 */
class CategoryProduct extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'status'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id');
    }

    public function scopeStoreData($query, $request)
    {
        # code...
        return $query->create([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }

    function scopeUpdateData($query, $category, $request)
    {
        #code...
        return $category->update([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }

    function scopeDeleteData($query, $id)
    {
        $data = $query->findOrfail($id);
        return $data->delete();
    }
}
