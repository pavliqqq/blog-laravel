<?php

function postData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Nature',
        'content' => 'There are a lot of blue rivers and lakes on the Earth',
        'category_id' => 99999,
    ], $overrides);
}
