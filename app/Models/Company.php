<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
/**
 * Class Company
 * @package App\Models
 * @version October 16, 2019, 11:46 am UTC
 */
class Company extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'companies';

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
        'name',
        'cnpj',
        'phone',
        'email',
        'photo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'cnpj'      => 'string',
        'phone'     => 'string',
        'email'     => 'string',
        'photo'     => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'      => 'required',
        'cnpj'      => 'required',
        'phone'     => 'required',
        'email'     => 'required',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'readable_created_at',
        'readable_updated_at',
    ];

    // =========================================================================
    // Relationships
    // =========================================================================

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function suppliers()
    {
        return $this->hasMany(\App\Models\Suppliers::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function stocks()
    {
        return $this->hasMany(\App\Models\Stocks::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function products()
    {
        return $this->hasMany(\App\Models\Products::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clients()
    {
        return $this->hasMany(\App\Models\Clients::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function attachments()
    {
        return $this->hasMany(\App\Models\Attachments::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function types()
    {
        return $this->hasMany(\App\Models\Type::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function wallets()
    {
        return $this->hasMany(\App\Models\Wallet::class, 'company_id');
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

    // =========================================================================
    // Methods
    // =========================================================================

    public function getPhotoAttribute()
    {
        return (is_null($this->getAttributes()["photo"]) || $this->getAttributes()["photo"]=="-")? asset('images/default/no_user.png') : $this->getAttributes()["photo"];
    }
    
    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('companies')
            ->singleFile();
    }
}
