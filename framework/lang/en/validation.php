<?php

return [
    'required' => 'The field ":field" is required.',
    'min' => [
        'numeric' => 'The ":field" field must not be less than :minValue.',
        'string' => 'The ":field" field must contain at least :minValue characters.',
    ],
    'max' => [
        'numeric' => 'The ":field" field must not be greater than :maxValue.',
        'string' => 'The ":field" field can contain a maximum of :maxValue characters.',
    ],
    'between' => [
        'numeric' => 'The ":field" field must be between :minValue and :maxValue.',
        'string' => 'The ":field" field must be between :minValue and :maxValue characters.',
    ],
    'string' => 'The field ":field" must be a string.',
    'email' => 'The field ":field" must be a valid email address.',
    'url' => 'The field ":field" must be a valid URL.',
    'numeric' => 'The field ":field" must be a number.',
    'alpha' => 'The ":field" field must contain only alphabetic characters.',
    'alpha_num' => 'The ":field" field must contain only alpha-numeric characters.',
    'alpha_dash' => 'The ":field" field may contain alpha-numeric characters, as well as dashes and underscores.',
    'unique' => 'The field ":field" must be unique.',
];
