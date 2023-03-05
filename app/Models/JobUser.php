<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','status'];

    public function jobs()
    {
        return $this->hasMany(JobUser::class);
    }


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function scopeStoreData($query, $request)
    {
        # code...
        return $query->create([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }

    public function scopeUpdateData($query, $job, $request)
    {
        # code...
        return $job->update([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }

    public function scopeDeleteData($query, $job)
    {
        # code...
        return $job->delete();
    }
}
