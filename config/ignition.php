<?php

/*
|--------------------------------------------------------------------------
| Ignition (development error page) configuration
|--------------------------------------------------------------------------
|
| Ignition is a require-dev package used only for local error pages. Its
| "runnable solutions" feature exposes POST /_ignition/execute-solution,
| which can execute arbitrary solutions against the application. That
| endpoint is the vector behind the well-known Ignition RCE family
| (CVE-2021-3129 and successors).
|
| We hard-disable it. The error page still renders useful stack traces in
| local development; it simply can no longer be asked to execute anything.
|
| If you ever need runnable solutions on a local machine, opt in explicitly
| with IGNITION_RUNNABLE_SOLUTIONS=true in that machine's .env. Never enable
| it on a host reachable by anyone but you.
|
*/

return [

    'enable_runnable_solutions' => env('IGNITION_RUNNABLE_SOLUTIONS', false),

    // Only expose the editor/file links when explicitly asked for.
    'editor' => env('IGNITION_EDITOR', 'phpstorm'),

    'remote_sites_path' => env('IGNITION_REMOTE_SITES_PATH', ''),

    'local_sites_path' => env('IGNITION_LOCAL_SITES_PATH', ''),

    'housekeeping' => [
        'enabled' => true,
    ],

];
