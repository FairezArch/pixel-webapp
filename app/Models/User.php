<?php

namespace App\Models;

use App\Models\FileUpload;
use Illuminate\Support\Str;
use App\Models\ModelHasRole;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $store_id
 * @property int $region_id
 * @property int $role_id
 * @property string $name
 * @property string $password
 * @property string $filename
 * @property string $mobile
 * @property string $email
 * @property string $email_verified_at
 * @property int $status
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Store $store
 * @property Region $region
 * @property Attendance[] $attendances
 * @property Sale[] $sales
 * @property Store[] $stores
 * @property Target[] $targets
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['store_id', 'device_id', 'name', 'password', 'filename', 'mobile', 'email', 'email_verified_at', 'status', 'remember_token'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function targets()
    {
        return $this->hasMany('App\Models\Target');
    }

    public function has_role()
    {
        return $this->hasOne('App\Models\ModelHasRole', 'model_id');
    }

    public function scopeCreateDataEmployee($query, $request)
    {
        # code...
        $media = new FileUpload();
        $folder = 'employee';
        $section = 'insert';
        $filename = $media->AddMedia($request->file, $folder, $section);
        $pass = Str::random(12);
        $role = Role::findOrFail($request->role);
        $store = $query->create([
            'name' => $request->name,
            'filename' => $filename,
            'password' =>  Hash::make($pass),
            'mobile' => $request->phone_number,
            'email' => $request->email,
            'store_id' => $request->store_id,
            'status' => $request->status
        ]);
        $store->assignRole($role->name);

        $email_data = array(
            'title' => 'Berikut Password untuk login ke pixel app',
            'email' => $request->email,
            'name' => $request->name,
            'password' => $pass
        );

        Mail::send('mail.mail', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject('Pemberitahuan Password')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        return $store;
    }

    public function scopeUpdateDataEmployee($query, $user, $request)
    {
        # code...
        $media = new FileUpload();
        $folder = 'employee';
        $section = 'update';

        $filename = $user->filename;
        if ($request->hasFile('file')) {
            $filename = $media->AddMedia($request->file, $folder, $section, $filename);
        }

        $role = Role::findOrFail($request->role);

        $user->update([
            'name' => $request->name,
            'filename' => $filename,
            'mobile' => $request->phone_number,
            'email' => $request->email,
            'store_id' => $request->store_id,
            'status' => $request->status
        ]);

        ModelHasRole::where('model_id', $user->id)->delete();
        $user->assignRole($role->name);

        return $user;
    }

    public function scopeDeleteDataEmployee($query, $user)
    {
        # code...
        $media = new FileUpload();
        $folder = 'employee';

        if (!empty($user->filename)) {
            $media->deleteMedia($folder, $user->filename);
        }

        return $user->delete();
    }

    public function scopeProfileUpdate($query, $user, $request)
    {

        $media = new FileUpload();
        $folder = 'employee';
        $section = 'update';

        $filename = $user->filename;
        if ($request->hasFile('file')) {
            $filename = $media->AddMedia($request->file, $folder, $section, $filename);
        }

        return $user->update(['filename' => $filename]);
    }

    // public static function boot()
    // {
    //     # code...
    //     Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    // }
}
