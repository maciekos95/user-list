<?php

return [
    'required' => 'Pole ":field" jest wymagane.',
    'min' => [
        'numeric' => 'Pole ":field" nie może być mniejsze niż :minValue.',
        'string' => 'Pole ":field" nie może mieć mniej znaków niż :minValue.',
    ],
    'max' => [
        'numeric' => 'Pole ":field" nie może być większe niż :maxValue.',
        'string' => 'Pole ":field" nie może mieć więcej znaków niż :maxValue.',
    ],
    'between' => [
        'numeric' => 'Pole ":field" musi mieć wartość pomiędzy :minValue i :maxValue.',
        'string' => 'Pole ":field" nie może mieć mniej niż :minValue i więcej niż :maxValue znaków.',
    ],
    'string' => 'Pole ":field" musi być ciągiem znaków.',
    'email' => 'Pole ":field" musi być poprawnym adresem e-mail.',
    'url' => 'Pole ":field" musi być poprawnym adresem URL.',
    'numeric' => 'Pole ":field" musi być liczbą.',
    'alpha' => 'Pole ":field" może zawierać tylko litery.',
    'alpha_num' => 'Pole ":field" może zawierać tylko znaki alfanumeryczne.',
    'alpha_dash' => 'Pole ":field" może zawierać tylko znaki alfanumeryczne, myślniki i podkreślenia.',
    'unique' => 'Pole ":field" musi być unikalne.',
];
