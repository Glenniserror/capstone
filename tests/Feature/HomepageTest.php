<?php

use function Pest\Laravel\get;

it('loads the homepage successfully', function () {
    get('/')->assertOk();
});
