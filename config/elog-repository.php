<?php
/*
 * configure URLs necessary for interacting with logbooks API
 *   api:      base url for API queries
 */
return [
    'api' => env('LOGBOOKS_API', 'https://logbooks.jlab.org/api/elog')
];
