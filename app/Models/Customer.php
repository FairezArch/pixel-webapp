<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property array $gender
 * @property int $age
 * @property string $job
 * @property Sale[] $sales
 */
class Customer extends Model
{

    use SoftDeletes, HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['nik','name', 'mobile', 'email', 'gender', 'age_id', 'job_id', 'ktp_filename'];
    
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }

    public function job()
    {
        return $this->belongsTo('App\Models\Job')->withDefault();
    }
    
    public function age()
    {
        return $this->belongsTo('App\Models\Age')->withDefault();
    }
}
