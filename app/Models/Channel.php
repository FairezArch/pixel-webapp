<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property Store[] $stores
 */
class Channel extends Model
{
    use SoftDeletes;
    
    /**
     * @var array
     */
    protected $fillable = ['name', 'status', 'region_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo('App\Models\Region')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stores()
    {
        return $this->hasMany('App\Models\Store');
    }

    public function scopeStoreData($query,$request)
    {
        # code...
        return $query->create([
            'name' => $request->name,
            'region_id' => $request->region,
            'status' => $request->status
        ]);
    }

    public function scopeUpdateData($query,$main_store,$request)
    {
        # code...
        return $main_store->update([
            'name' => $request->name,
            'region_id' => $request->region,
            'status' => $request->status
        ]);
    }

    public function scopeDeleteData($query,$main_store)
    {
        # code...
        return $main_store->delete();
    }
}
