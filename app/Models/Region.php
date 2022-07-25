<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property User[] $users
 */
class Region extends Model
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
    public function channels()
    {
        return $this->hasMany('App\Models\Channel');
    }

    public function scopeStoreData($query,$request)
    {
        return $query->create([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }

    public function scopeUpdateData($query,$region,$request)
    {
        # code...
        return $region->update([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }

    public function scopeDeleteData($query,$region)
    {
        # code...
        return $region->delete();
    }
}
