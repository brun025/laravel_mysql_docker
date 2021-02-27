<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Carbon\Carbon;

/**
 * Class User
 * @package App\Models
 * @version October 16, 2019, 11:53 am UTC
 */
class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use HasRoles;
    use HasMediaTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'company_id',
        'name',
        'cpf',
        'email',
        'password',
        'phone',
        'photo',
        'status_user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'status_user_id'    => 'integer',
        'company_id'        => 'integer',
        'name'              => 'string',
        'cpf'               => 'string',
        'email'             => 'string',
        'password'          => 'string',
        'phone'             => 'string',
        'photo'             => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'       => 'required',
        'cpf'        => 'required',
        'email'      => 'required',
        'phone'      => 'required'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'readable_created_at',
        'readable_updated_at',
        'readable_company_name',
        'readable_role_name',
    ];

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function suppliers()
    {
        return $this->belongsToMany(\App\Models\Supplier::class, 'supplier_user', 'user_id', 'supplier_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function clients()
    {
        return $this->belongsToMany(\App\Models\Client::class, 'client_user', 'user_id', 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function providers()
    {
        return $this->belongsToMany(\App\Models\Provider::class, 'provider_user', 'user_id', 'provider_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function statusUser()
    {
        return $this->belongsTo(\App\Models\StatusUser::class, 'status_user_id', 'id');
    }

    // =========================================================================
    // Getters
    // =========================================================================

    /**
     * Get created_at formatted as d/m/Y | H:i
     *
     * @return string
     */
    public function getReadableCreatedAtAttribute()
    {
        return is_null($this->created_at)? null : Carbon::parse($this->created_at)->format('d/m/Y | H:i');
    }

    /**
     * Get updated_at formatted as d/m/Y | H:i
     *
     * @return string
     */
    public function getReadableUpdatedAtAttribute()
    {
        return is_null($this->updated_at)? null : Carbon::parse($this->updated_at)->format('d/m/Y | H:i');
    }

    /**
     * Get company name as company_name
     *
     * @return string
     */
    public function getReadableCompanyNameAttribute()
    {
        if ($this->company_id){
            $company = Company::find($this->company_id);
            $companyName = $company->name;
            return $companyName;
        }else{
            return null;
        }

    }

    /**
     * Get role_name as stored in db
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return $this->getRoleNames()->first();
    }

    /**
     * Get role_name for humans
     * *WARNING* Any change here must be replicated to App\DataTables\UserDataTable
     *
     * @return string
     */
    public function getReadableRoleNameAttribute()
    {
        $roleName = strtoupper($this->role_name);
        return is_null($roleName)? null : config("enums.roles.$roleName.display_name");
    }

    /**
     * Get photo or return default photo
     *
     * @return string
     */
    public function getPhotoAttribute()
    {
        return (is_null($this->getAttributes()["photo"]) || $this->getAttributes()["photo"]=="-")? asset('images/default/no_user.png') : $this->getAttributes()["photo"];
    }

    // =========================================================================
    // Methods
    // =========================================================================

    public function registerMediaCollections()
    {
        $this->addMediaCollection('users')->singleFile();
    }
}
