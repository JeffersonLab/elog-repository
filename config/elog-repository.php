<?php
/*
 * configure parameters necessary for interacting with logbooks API
 *   api:      base url for API queries
 *   timeout:  max number of seconds allowed for response from http connections to logbook server
 */
return [
    'api' => env('LOGBOOKS_API', 'https://logbooks.jlab.org/api/elog'),
    'timeout' => env('LOGBOOKS_TIMEOUT', 10),
];
