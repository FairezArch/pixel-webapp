<?php

namespace App\Models;

use App\Http\Requests\AttendanceRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $user_id
 * @property int $clock_in
 * @property int $clock_out
 * @property string $location
 * @property User $user
 */
class Attendance extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'clock_in', 'clock_out', 'location', 'in_location', 'out_location'];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'location' => 'array'
    ];

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

    private function updatedClockOut()
    {
    }

    public function scopeAttend(Builder $query, AttendanceRequest $request)
    {
        //$store = 
        ini_set('serialize_precision', -1);
        $attend = $query->latest()->firstOrNew([
            'user_id' => Auth::id(),
            'clock_out' => null
        ], [
            'clock_in' => Carbon::now(),
        ]);
        if ($attend->exists) {
            $attend->clock_out = Carbon::now();
            $appended = $attend->location;
            $appended[] = $request->location;
            $attend->location = $appended;
        } else
            $attend->location = [
                $request->location
            ];
        $attend->save();

        return $attend;
    }

    public function scopeGetData(Builder $query, $request)
    {
        $attend = $query->latest()->firstWhere(['user_id' => Auth::id(),'clock_out' => null]);
        return $attend;
    }


    protected static function booted()
    {
        function getDistance($location): ?float
        {
            $store = Store::firstWhere('id', Auth::user()->store_id);
            /* dd(DB::select('SELECT GET_DISTANCE(?, ?, ?, ?, "K") AS distance', [
                $store->location[0], $store->location[1],
                $location[0], $location[1]
            ])); */
            return
                Arr::pluck(DB::select('SELECT GET_DISTANCE(?, ?, ?, ?, "K") AS distance', [
                    $store->location[0], $store->location[1],
                    $location[0], $location[1]
                ]), 'distance')[0];
            // to be plucked
        }

        static::creating(function (Attendance $attend) {
            $distance = getDistance($attend->location[0]);
            $attend->in_location = $distance <= config('app.settings.max_distance') ? 1 : 0;
            // dd($distance, $distance <= config('app.settings.max_distance'), config('app.settings.max_distance'));
        });

        static::updating(function (Attendance $attend) {
            $distance = getDistance($attend->location[1]);
            $attend->out_location = $distance <= config('app.settings.max_distance') ? 1 : 0;
        });
    }
}
