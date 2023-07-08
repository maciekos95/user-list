<?php

namespace App\Models;

use Framework\Classes\Model;
use Framework\Traits\Validation;

class User extends Model
{
    use Validation;

    /**
     * @var string The name of the dataset corresponding to the model.
     */
    protected $dataset = 'users';

    /**
     * @var array The array of fillable model attributes.
     */
    protected $fillable = ['name', 'username', 'email', 'address', 'phone', 'website', 'company'];

    /**
     * @var array The validation rules for the model attributes.
     */
    protected $rules = [
        'name' => 'required|string',
        'username' => 'required|alpha_dash|between:3,20|unique',
        'email' => 'required|email',
        'phone' => 'required|string',
        'website' => 'string',
        'address.street' => 'required|string',
        'address.suite' => 'required|string',
        'address.city' => 'required|string',
        'address.zipcode' => 'required|string',
        'address.geo.lat' => 'numeric|between:-90,90',
        'address.geo.lng' => 'numeric|between:-180,180',
        'company.name' => 'required|string',
        'company.catchPhrase' => 'string',
        'company.bs' => 'string',
    ];

    /**
     * @var string The language path for validation error messages.
     */
    protected $langPath = 'app.form';
}
