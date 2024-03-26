<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlasmaRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'gender', 'age', 'blood_group', 'covid_positive_date', 'covid_negative_date', 'phone_number', 'country_id', 'state_id'
    ];

    /**
     * Get the country of the plasma request.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the state of the plasma request.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city of the plasma request.
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }    

}
