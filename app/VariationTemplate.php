<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VariationTemplate extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the attributes for the variation.
     */
    public function values()
    {
        return $this->hasMany(\App\VariationValueTemplate::class);
    }

    public static function forDropdown($business_id)
    {
        $variations = self::where('business_id', $business_id)
            ->join('variation_value_templates', 'variation_templates.id', '=', 'variation_value_templates.variation_template_id')
            ->pluck('variation_value_templates.name', 'variation_value_templates.id');
        
        return $variations;
    }
}
