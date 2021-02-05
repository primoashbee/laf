<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Branch implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $branches = [
        "ANGELES",
        "BALAYAN",
        "BALIWAG",
        "BATANGAS",
        "BINAN",
        "BINANGONAN",
        "BUTUAN",
        "CALAMBA",
        "CAPAS,TARLAC",
        "CAUAYAN",
        "CEBU SOUTH",
        "DAET",
        "DAGUPAN",
        "DASMARINAS",
        "DAVAO",
        "DIGOS",
        "GAPAN",
        "GUAGUA",
        "GENSAN",
        "GUMACA",
        "ILIGAN",
        "IMUS",
        "IRIGA",
        "KABANKALAN",
        "KIDAPAWAN",
        "LAPU-LAPU",
        "LEGAZPI",
        "LIPA",
        "LUCENA",
        "MALOLOS",
        "MARIKINA",
        "MEYCAUAYAN",
        "NAGA",
        "ORMOC",
        "PARANAQUE",
        "PASAY",
        "PINAMALAYAN",
        "PASIG",
        "QUEZON CITY",
        "ROSARIO",
        "SAN CARLOS",
        "SAN FERNANDO, LU",
        "SAN FRANCISCO",
        "SAN PABLO",
        "STA. CRUZ",
        "SILAY",
        "TAGAYTAY",
        "TALAVERA",
        "TARLAC",
        "TACLOBAN",
        "TAGBILARAN",
        "TAGUM",
        "TUGUEGARAO",
        "URDANETA",
        "VALENZUELA"
        ];

        return in_array($value,$branches) ?  true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Branch selected is not included on list.';
    }
}
