<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    public static function forDropdown($business_id)
    {
        $charges = Charge::where('business_id', $business_id)->get();
        $dropdown = [];

        foreach ($charges as $charge) {
            $dropdown[$charge->id] = $charge->name;
        }

        return $dropdown;
    }

    /**
     * Get the formatted display name of the charge.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->name;
    }
}
