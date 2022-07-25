<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property integer $nominal
 * @property string $date
 * @property User $user
 */
class Target extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'nominal', 'date'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    public function scopeGetPercentage($query, $target, $param)
    {
        if ($param) {
            $currentSales = $target->user->sales()->whereDate('created_at', $param)->sum('nominal');
        } else {
            $currentSales = $target->user->sales()->whereDate('created_at', now())->sum('nominal');
        }

        $currentTarget = $target->nominal;
        $percentage = round($currentSales / $currentTarget * 100);

        $currentSales = 'Rp. ' . number_format($currentSales, 2);
        $currentTarget = 'Rp. ' . number_format($currentTarget, 2);
        return compact('percentage', 'currentSales', 'currentTarget');
    }

    public function scopeGetCurrent(Builder $query, Request $request)
    {
        $target = $query
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('targets.date', '<=', $request->date);
                $query->where('user_id', Auth::user()->id);
            })
            ->unless($request->date, function ($query) {
                $query->whereDate('targets.date', '<=', now());
                $query->where('user_id', Auth::user()->id);
            })->orderByDesc('date')->get();

        if ($target->isEmpty()) {
            $percentage = 0;
            $currentSales = 'Rp. ' . number_format(0, 2);
            $currentTarget = 'Rp. ' . number_format(0, 2);
            return compact('percentage', 'currentSales', 'currentTarget');
        } else {
            return $target->first()->GetPercentage($target->first(), $request->date);
        }
    }

    public function scopeStoreData($query, $request)
    {
        # code...
        $nominal = filter_var(str_replace(".", "", $request->nominal), FILTER_SANITIZE_NUMBER_INT);
        return $query->create([
            'user_id' => $request->user_id,
            'nominal' => $nominal,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function scopeUpdateData($query, $target, $request)
    {
        # code...
        $nominal = filter_var(str_replace(".", "", $request->nominal), FILTER_SANITIZE_NUMBER_INT);
        return $target->update([
            'user_id' => $request->user_id,
            'nominal' => $nominal,
            'date' => Carbon::now()->format('Y-m-d'),
            'status' => $request->status
        ]);
    }

    public function scopeDeleteData($query, $target)
    {
        # code...
        return $target->delete();
    }
}
