<?php

namespace App\Models;

use App\Casts\JsonCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FileUpload;

/**
 * @property int $id
 * @property int $channel_id
 * @property array $promoter_ids
 * @property array $frontliner_ids
 * @property string $store_name
 * @property array $location
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property Channel $channel
 * @property Sale[] $sales
 * @property User[] $users
 */
class Store extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['channel_id', 'promoter_ids', 'frontliner_ids', 'name', 'filename', 'location', 'maps_location', 'status'];

    protected $casts = [
        'location' => 'array',
        'promoter_ids' => 'array',
        'frontliner_ids' => 'array'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /* public function setOptionsAttribute($fields) {
        $this->attributes['location'] = json_encode($fields);
    } */

    /* protected static function booted() {
        static::creating(function(Store $store) {
            if (is_array($store->location))
                $store->location = json_encode($store->location);

            if (is_array($store->promoter_ids))
                $store->promoter_ids = json_encode($store->promoter_ids);

            if (is_array($store->frontliner_ids))
                $store->frontliner_ids = json_encode($store->frontliner_ids);
        });
    } */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('App\Models\Channel')->withDefault();
    }
    
    public function scopeStoreData($query, $request)
    {
        # code...
        // return dd($request);
        ini_set('serialize_precision', -1);
        $media = new FileUpload();
        $folder = 'store';
        $section = 'insert';
        $url = $request->location;
        $spliturl = explode('@', $url);
        $at = explode('z', $spliturl[1]);
        $zero = explode(',', $at[0]);
        $lat = (double)$zero[0];
        $log = (double)$zero[1];
        $arr_location = array($lat, $log);
        // $promoter_ids = array_map('intval',$request->promoter_ids);
        // $frontliner_ids = array_map('intval',$request->frontliner_ids);

        // $url = "https://www.google.com/maps/place/Banaran,+Pabelan,+Kec.+Kartasura,+Kabupaten+Sukoharjo,+Jawa+Tengah/@-7.5620968,110.7615535,16z/data=!3m1!4b1!4m12!1m6!3m5!1s0x2e7a152891505e29:0x498c199e13c6a559!2sGROSIR+IKAN+SEGAR+GRM!8m2!3d-7.5341505!4d110.7885763!3m4!1s0x2e7a1450cad77aef:0x2db2303b1ea09eb1!8m2!3d-7.5618881!4d110.7673768";
        // $preg = preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $lat);
        // $preg = preg_match('/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,20}$/', $lat);
        // dd((double)$lat, (double)$log, $lat, floor($lat));
        //-7.5620968,110.7615535
        // dd($request->all());

        $filename = $media->AddMedia($request->file, $folder, $section);

        return $query->create([
            // 'promoter_ids' => $promoter_ids,
            // 'frontliner_ids' => $frontliner_ids,
            'channel_id' => $request->channel_id,
            'name' => $request->name,
            'filename' => $filename,
            'location' => $arr_location,
            'maps_location' => $request->location,
            'status' => $request->status
        ]);
    }

    public function scopeUpdateData($query, $store, $request)
    {
        # code...
        ini_set('serialize_precision', -1);
        $media = new FileUpload();
        $folder = 'store';
        $section = 'update';

        $filename = $store->filename;

        if ($request->hasFile('file')) {
            $filename = $media->AddMedia($request->file, $folder, $section, $filename);
        }

        // $promoter_ids = array_map('intval',$request->promoter_ids);
        // $frontliner_ids = array_map('intval',$request->frontliner_ids);

        $url = $request->location;
        $spliturl = explode('@', $url);
        $at = explode('z', $spliturl[1]);
        $zero = explode(',', $at[0]);
        $lat = (double)$zero[0];
        $log = (double)$zero[1];
        $arr_location = array($lat, $log);

        return $store->update([
            // 'promoter_ids' => $promoter_ids,
            // 'frontliner_ids' => $frontliner_ids,
            'channel_id' => $request->channel_id,
            'name' => $request->name,
            'filename' => $filename,
            'location' => $arr_location,
            'maps_location' => $request->location,
            'status' => $request->status
        ]);
    }

    public function scopeDeleteData($query, $store)
    {
        # code...
        $media = new FileUpload();
        $folder = 'store';

        if (!empty($store->filename)) {
            $media->deleteMedia($folder, $store->filename);
        }

        return $store->delete();
    }
}
