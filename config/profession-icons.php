<?php


$professionsConfig = config('professions', []);

return [
    'politics' => [
        'name' => 'Politics & Government',
        'icon' => '🏛️',
        'professions' => $professionsConfig['politician'] ?? []
    ],
    'entertainment' => [
        'name' => 'Entertainment',
        'icon' => '🎭',
        'professions' => config('professions.filmographies', [])
    ],
    'sports' => [
        'name' => 'Sports',
        'icon' => '⚽',
        'professions' => config('professions.sports', [])
    ],
    'business' => [
        'name' => 'Business & Entrepreneurship',
        'icon' => '💼',
        'professions' => config('professions.entrepreneur', [])
    ],
    'science' => [
        'name' => 'Science & Technology',
        'icon' => '🔬',
        'professions' => config('professions.science', [])
    ],

    'media' => [
        'name' => 'Media & Journalism',
        'icon' => '📰',
        'professions' => config('professions.literature', [])
    ],
    'law' => [
        'name' => 'Law & Justice',
        'icon' => '⚖️',
        'professions' => config('professions.law', [])
    ]
];
