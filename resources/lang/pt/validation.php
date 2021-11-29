<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute tem de ser aceite.',
    'active_url'           => ':attribute não é um URL válido.',
    'after'                => ':attribute must be a date after :date.',
    'alpha'                => ':attribute apenas pode conter letras.',
    'alpha_dash'           => ':attribute apenas pode conter letras, números, hífens (-) e underscores (_).',
    'alpha_num'            => ':attribute apenas pode conter letras e números.',
    'array'                => ':attribute tem de ser um array.',
    'before'               => ':attribute tem de ser uma data antes de :date.',
    'between'              => [
        'numeric' => ':attribute tem que estar entre :min e :max.',
        'file'    => ':attribute tem que estar entre :min e :max kilobytes.',
        'string'  => ':attribute tem que estar entre :min e :max caracteres.',
        'array'   => ':attribute tem que estar entre :min e :max items.',
    ],
    'boolean'              => 'Campo :attribute tem ser verdadeiro ou falso.',
    'confirmed'            => 'Campo :attribute de confirmação não é igual.',
    'date'                 => ':attribute não é uma data válida.',
    'date_format'          => ':attribute não coincide com o formato :format.',
    'different'            => ':attribute e :other têm que ser diferentes.',
    'digits'               => ':attribute tem que ser :digits dígitos.',
    'digits_between'       => ':attribute tem que estar entre :min e :max dígitos.',
    'distinct'             => 'O campo :attribute tem valores duplicados.',
    'email'                => ':attribute tem que ser um endereço de email válido.',
    'exists'               => ':attribute selecionado é inválido.',
    'filled'               => 'O campo :attribute é necessário.',
    'image'                => ':attribute tem que ser uma imagem.',
    'in'                   => ':attribute seleccionado é inválido.',
    'in_array'             => 'O campo :attribute não existe em :other.',
    'integer'              => ':attribute tem que ser um número inteiro.',
    'ip'                   => ':attribute tem que ser um endereço IP válido.',
    'json'                 => ':attribute tem que ser um JSON string válido.',
    'max'                  => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file'    => 'O campo :attribute não pode ser maior que :max kilobytes.',
        'string'  => 'O campo :attribute não pode ser maior que :max caracteres.',
        'array'   => 'O campo :attribute não pode ser maior que :max items.',
    ],
    'mimes'                => ':attribute tem que ser um ficheiro tipo: :values.',
    'min'                  => [
        'numeric' => 'O campo :attribute tem que ter pelo menos :min.',
        'file'    => 'O campo :attribute tem que ter pelo menos :min kilobytes.',
        'string'  => 'O campo :attribute tem que ter pelo menos :min caracteres.',
        'array'   => 'O campo :attribute tem que ter pelo menos :min items.',
    ],
    'not_in'               => ':attribute seleccionado é inválido.',
    'numeric'              => ':attribute tem que ser um número.',
    'present'              => 'O campo :attribute tem que estar presente.',
    'regex'                => ':attribute formato é inválido.',
    'required'             => 'O campo :attribute é necessário.',
    'required_if'          => 'O campo :attribute é necessário quando :other é :value.',
    'required_unless'      => 'O campo :attribute é necessário a não ser que :other esteja entre :values.',
    'required_with'        => 'O campo :attribute é necessário quando :values está presente.',
    'required_with_all'    => 'O campo :attribute é necessário quando :values está present.',
    'required_without'     => 'O campo :attribute é necessário quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é necessário quando nenhum de :values estão presentes.',
    'same'                 => ':attribute e :other têm que coincidir.',
    'size'                 => [
        'numeric' => ':attribute tem que ter :size.',
        'file'    => ':attribute tem que ter :size kilobytes.',
        'string'  => ':attribute tem que ter :size caracteres.',
        'array'   => ':attribute tem que conter :size items.',
    ],
    'string'               => ':attribute tem que ser um string.',
    'timezone'             => ':attribute tem que ser uma zona válida.',
    'unique'               => ':attribute já foi atribuido.',
    'url'                  => ':attribute formato é inválido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
