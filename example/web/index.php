<?php
    require_once  __DIR__.'/../app/config/configuration.php';

    if (ACTIVE_PROFILE === 'production') {
        require_once __DIR__.'/../app/app.php';
    } else {
        // Next iteration of the sample application
        require_once __DIR__.'/../app/app2.php';
    }
