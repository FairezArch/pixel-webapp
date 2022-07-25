<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FileUpload;

/**
 * @property int $id
 * @property int $category_id
 * @property int $color_id
 * @property string $name
 * @property string $filename
 * @property float $price
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property CategoryProduct $categoryProduct
 * @property Sale[] $sales
 */
class Product extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['category_id', 'color_ids', 'code', 'name', 'filename', 'price', 'takeImei', 'status'];

    protected $casts = [
        'color_ids' => 'array'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getColorIdsAttribute($val)
    {
        return $val ? json_decode($val) : [$val];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category_product()
    {
        return $this->belongsTo('App\Models\CategoryProduct', 'category_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }

    public function scopeGetColors($query)
    {
        return $query->select('color')->where('color', '!=', '')->distinct()->get();
    }

    public function scopeStoreData($query, $request)
    {
        # code...
        $media = new FileUpload();
        $folder = 'product';
        $section = 'insert';
        $filename = $request->hasFile('file') ? $media->AddMedia($request->file, $folder, $section) : null;
        $price = filter_var(str_replace(".", "", $request->price), FILTER_SANITIZE_NUMBER_INT);
        $color = array_map('intval', $request->color);

        return $query->create([
            'category_id' => $request->category_id,
            'code' => $request->code,
            'name' => $request->name,
            'filename' => $filename,
            'price' => $price,
            'color_ids' => $color,
            'takeImei' => $request->need_image,
            'status' => $request->status
        ]);
    }

    function scopeUpdateData($query, $product, $request)
    {
        #code...
        $media = new FileUpload();
        $folder = 'product';
        $section = 'update';

        $filename = $product->filename;

        if ($request->hasFile('file')) {
            $filename = $media->AddMedia($request->file, $folder, $section);
        }
        // $req = str_replace('.00','',$request->price);
        $price = filter_var(str_replace(".", "", $request->price), FILTER_SANITIZE_NUMBER_INT);
        // dd($request->status);
        $color = array_map('intval', $request->color);

        return $product->update([
            'category_id' => $request->category_id,
            'code' => $request->code,
            'name' => $request->name,
            'filename' => $filename,
            'price' => $price,
            'color_ids' => $color,
            'takeImei' => $request->need_image,
            'status' => $request->status
        ]);
    }

    function scopeDeleteData($query, $product)
    {
        #code...
        $media = new FileUpload();
        $folder = 'product';

        if (!empty($product->filename)) {
            $media->deleteMedia($folder, $product->filename);
        }

        return $product->delete();
    }
}
