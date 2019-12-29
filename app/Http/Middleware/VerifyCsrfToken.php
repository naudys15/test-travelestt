<?php

namespace Travelestt\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/login',
        'api/logout',
        'api/coasts/changeStatus/*',
        'api/festivals/changeStatus/*',
        'api/museums/changeStatus/*',
        'api/points_of_interest/changeStatus/*',
        'api/night_spots/changeStatus/*',
        'api/routes/changeStatus/*',
        'api/street_markets/changeStatus/*',
        'api/shows/changeStatus/*',
        'api/natural_spaces/changeStatus/*',
        'api/coasts/changeOutstanding/*',
        'api/festivals/changeOutstanding/*',
        'api/museums/changeOutstanding/*',
        'api/points_of_interest/changeOutstanding/*',
        'api/night_spots/changeOutstanding/*',
        'api/routes/changeOutstanding/*',
        'api/street_markets/changeOutstanding/*',
        'api/shows/changeOutstanding/*',
        'api/natural_spaces/changeOutstanding/*',
        'api/experiences/changeOutstanding/*',
        'api/cities/changeOutstanding/*',
        'api/cities/changeTopDestination/*',
    ];
}
