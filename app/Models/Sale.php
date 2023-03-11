<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Color;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GetSaleRequest;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property int $product_id
 * @property int $customer_id
 * @property int $quantity
 * @property integer $nominal
 * @property string $imei_filename
 * @property string $photo_filename
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Product $product
 * @property Customer $customer
 * @property Store $store
 * @property User $user
 */
class Sale extends Model
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'store_id', 'product_id', 'customer_id', 'quantity', 'nominal', 'imei_filename', 'photo_filename', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = [
        // 'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('m/d/Y H:i');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store')->withDefault();
    }

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
    public function color()
    {
        return $this->belongsTo('App\Models\Color')->withDefault();
    }

    public function scopeStoreData(Builder $query, StoreSaleRequest $request)
    {

        $now = Carbon::now()->toDateTimeString();
        $order_code = Str::random(12);

        $customer = Customer::create(
            [
                'name' => $request->customer_name,
                'mobile' => $request->customer_mobile,
                'email' => $request->customer_email,
                'gender' => $request->customer_gender,
                'age_id' => $request->customer_age,
                'job_id' => $request->customer_job,
            ]
        );

        $list = [];

        foreach ($request->products as $key => $value) {

            $checkColor = Color::where('code_color',$value['color'])->first();
            if(empty($checkColor) || empty($value['color'])){
                $color = 0;
            }else{
                $color = $checkColor->id;
            }


            $currentPrice = intval(Product::select(['price'])
                ->firstWhere('id', $value['product_id'])->price);

            $list[$key]['order_code'] = $order_code;
            $list[$key]['user_id'] = $request->user()->id;
            $list[$key]['store_id'] = $request->user()->store_id;
            $list[$key]['product_id'] = $value['product_id'];
            $list[$key]['color_id'] = $color;
            $list[$key]['customer_id'] = $customer->id;
            $list[$key]['quantity'] = 1;
            $list[$key]['nominal'] = 1 * $currentPrice;
            $list[$key]['imei_filename'] = $value['imei'];
            $list[$key]['created_at'] = $now;
            $list[$key]['updated_at'] = $now;
        }

        return $query->insert($list);
    }

    public function number_format_short($n, $precision = 1)
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'rb';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'Jt';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'M';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return floatval($n_format);
    }

    public function scopeGetData(Builder $query, GetSaleRequest $request)
    {
        $from = null;
        $to = null;
        if (!$request->has('byMonth')) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = Carbon::rawParse($request->byMonth)->startOfMonth();
            $to = Carbon::rawParse($request->byMonth)->lastOfMonth();
        }
        $aggr = DB::query()->fromSub(function ($q) use ($request, $from, $to) {
            $q->from('sales')
                ->where('user_id', $request->user()->id);
            if ($request->has('byMonth'))
                $q->whereMonth('created_at', Carbon::rawParse($request->byMonth));
            else
                $q->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            $q->selectRaw(
                // 'DATE(created_at) AS created_at, nominal, FLOOR(ROW_NUMBER() OVER (ORDER BY created_at) / (COUNT(*) OVER() / ?) % ?) AS seq',
                'DATE(created_at) AS sale_date, SUM(nominal) AS sale_nominal,
                FLOOR((DATEDIFF(created_at, ?) + 1) / ((DATEDIFF(?, ?) + 1) / ?)) AS seq',
                [
                    $from,
                    $to,
                    $from,
                    $request->dividedBy
                ]
            );
            $q->groupByRaw('DATE(created_at)');
            $q->orderBy('created_at');
        }, 'sale');
        if ($request->mean)
            $aggr->selectRaw('AVG(sale_nominal) as sale_nominal, MIN(sale_date) AS sale_date, IF(AVG(sale_nominal) >= targets.nominal, TRUE, FALSE) AS target_achieved');
        else
            $aggr->selectRaw('sale_nominal, MIN(sale_date) AS sale_date, IF(AVG(sale_nominal) >= targets.nominal, TRUE, FALSE) AS target_achieved');
        $aggr->join('targets', 'sale_date', '>=', 'targets.date')
            ->where('user_id', $request->user()->id);
        $aggr->groupBy('seq');
        return $aggr->get()->keyBy('sale_date')->mapWithKeys(function ($item, $key) {
            $nominal = $this->number_format_short(floatval($item->sale_nominal));
            return [
                $key => [
                    'date' => $key,
                    'sale_nominal' => $nominal,
                    'target_achieved' => $item->target_achieved
                ]
            ];
        })->values();
        // return $aggr->get()->keyBy('sale_date')->map(function ($item, $key) {
        //     unset($item->sale_date);
        //     return $item;
        // });
    }
}
