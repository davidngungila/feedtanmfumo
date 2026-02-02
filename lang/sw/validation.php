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

    'accepted' => 'Sehemu ya :attribute lazima ikubaliwe.',
    'accepted_if' => 'Sehemu ya :attribute lazima ikubaliwe wakati :other ni :value.',
    'active_url' => 'Sehemu ya :attribute lazima iwe URL halali.',
    'after' => 'Sehemu ya :attribute lazima iwe tarehe baada ya :date.',
    'after_or_equal' => 'Sehemu ya :attribute lazima iwe tarehe baada ya au sawa na :date.',
    'alpha' => 'Sehemu ya :attribute lazima iwe na herufi tu.',
    'alpha_dash' => 'Sehemu ya :attribute lazima iwe na herufi, nambari, dashes, na underscores tu.',
    'alpha_num' => 'Sehemu ya :attribute lazima iwe na herufi na nambari tu.',
    'any_of' => 'Sehemu ya :attribute si halali.',
    'array' => 'Sehemu ya :attribute lazima iwe safu.',
    'ascii' => 'Sehemu ya :attribute lazima iwe na alphanumeric characters na alama za single-byte tu.',
    'before' => 'Sehemu ya :attribute lazima iwe tarehe kabla ya :date.',
    'before_or_equal' => 'Sehemu ya :attribute lazima iwe tarehe kabla ya au sawa na :date.',
    'between' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vitu kati ya :min na :max.',
        'file' => 'Sehemu ya :attribute lazima iwe kati ya :min na :max kilobytes.',
        'numeric' => 'Sehemu ya :attribute lazima iwe kati ya :min na :max.',
        'string' => 'Sehemu ya :attribute lazima iwe kati ya :min na :max herufi.',
    ],
    'boolean' => 'Sehemu ya :attribute lazima iwe kweli au si kweli.',
    'can' => 'Sehemu ya :attribute ina thamani isiyoidhinishwa.',
    'confirmed' => 'Uthibitisho wa sehemu ya :attribute haufanani.',
    'contains' => 'Sehemu ya :attribute haitoshi thamani inayohitajika.',
    'current_password' => 'Nenosiri si sahihi.',
    'date' => 'Sehemu ya :attribute lazima iwe tarehe halali.',
    'date_equals' => 'Sehemu ya :attribute lazima iwe tarehe sawa na :date.',
    'date_format' => 'Sehemu ya :attribute lazima ifuate muundo :format.',
    'decimal' => 'Sehemu ya :attribute lazima iwe na maeneo :decimal ya desimali.',
    'declined' => 'Sehemu ya :attribute lazima ikataliwe.',
    'declined_if' => 'Sehemu ya :attribute lazima ikataliwe wakati :other ni :value.',
    'different' => 'Sehemu ya :attribute na :other lazima ziwe tofauti.',
    'digits' => 'Sehemu ya :attribute lazima iwe na tarakimu :digits.',
    'digits_between' => 'Sehemu ya :attribute lazima iwe na tarakimu kati ya :min na :max.',
    'dimensions' => 'Sehemu ya :attribute ina vipimo visivyofaa vya picha.',
    'distinct' => 'Sehemu ya :attribute ina thamani ya mara mbili.',
    'doesnt_contain' => 'Sehemu ya :attribute haipaswi kuwa na yafuatayo: :values.',
    'doesnt_end_with' => 'Sehemu ya :attribute haipaswi kuishia na yafuatayo: :values.',
    'doesnt_start_with' => 'Sehemu ya :attribute haipaswi kuanza na yafuatayo: :values.',
    'email' => 'Sehemu ya :attribute lazima iwe anwani halali ya barua pepe.',
    'encoding' => 'Sehemu ya :attribute lazima iwe encoded katika :encoding.',
    'ends_with' => 'Sehemu ya :attribute lazima iishie na yafuatayo: :values.',
    'enum' => ':attribute iliyochaguliwa si halali.',
    'exists' => ':attribute iliyochaguliwa si halali.',
    'extensions' => 'Sehemu ya :attribute lazima iwe na moja ya viambatisho vifuatavyo: :values.',
    'file' => 'Sehemu ya :attribute lazima iwe faili.',
    'filled' => 'Sehemu ya :attribute lazima iwe na thamani.',
    'gt' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vitu zaidi ya :value.',
        'file' => 'Sehemu ya :attribute lazima iwe kubwa kuliko :value kilobytes.',
        'numeric' => 'Sehemu ya :attribute lazima iwe kubwa kuliko :value.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi zaidi ya :value.',
    ],
    'gte' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vitu :value au zaidi.',
        'file' => 'Sehemu ya :attribute lazima iwe kubwa kuliko au sawa na :value kilobytes.',
        'numeric' => 'Sehemu ya :attribute lazima iwe kubwa kuliko au sawa na :value.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi :value au zaidi.',
    ],
    'hex_color' => 'Sehemu ya :attribute lazima iwe rangi halali ya hexadecimal.',
    'image' => 'Sehemu ya :attribute lazima iwe picha.',
    'in' => ':attribute iliyochaguliwa si halali.',
    'in_array' => 'Sehemu ya :attribute lazima iwepo katika :other.',
    'in_array_keys' => 'Sehemu ya :attribute lazima iwe na angalau ufunguo mmoja wa yafuatayo: :values.',
    'integer' => 'Sehemu ya :attribute lazima iwe nambari kamili.',
    'ip' => 'Sehemu ya :attribute lazima iwe anwani halali ya IP.',
    'ipv4' => 'Sehemu ya :attribute lazima iwe anwani halali ya IPv4.',
    'ipv6' => 'Sehemu ya :attribute lazima iwe anwani halali ya IPv6.',
    'json' => 'Sehemu ya :attribute lazima iwe string halali ya JSON.',
    'list' => 'Sehemu ya :attribute lazima iwe orodha.',
    'lowercase' => 'Sehemu ya :attribute lazima iwe lowercase.',
    'lt' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vitu chini ya :value.',
        'file' => 'Sehemu ya :attribute lazima iwe ndogo kuliko :value kilobytes.',
        'numeric' => 'Sehemu ya :attribute lazima iwe ndogo kuliko :value.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi chini ya :value.',
    ],
    'lte' => [
        'array' => 'Sehemu ya :attribute haipaswi kuwa na vitu zaidi ya :value.',
        'file' => 'Sehemu ya :attribute lazima iwe ndogo kuliko au sawa na :value kilobytes.',
        'numeric' => 'Sehemu ya :attribute lazima iwe ndogo kuliko au sawa na :value.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi chini ya au sawa na :value.',
    ],
    'mac_address' => 'Sehemu ya :attribute lazima iwe anwani halali ya MAC.',
    'max' => [
        'array' => 'Sehemu ya :attribute haipaswi kuwa na vitu zaidi ya :max.',
        'file' => 'Sehemu ya :attribute haipaswi kuwa kubwa kuliko :max kilobytes.',
        'numeric' => 'Sehemu ya :attribute haipaswi kuwa kubwa kuliko :max.',
        'string' => 'Sehemu ya :attribute haipaswi kuwa na herufi zaidi ya :max.',
    ],
    'max_digits' => 'Sehemu ya :attribute haipaswi kuwa na tarakimu zaidi ya :max.',
    'mimes' => 'Sehemu ya :attribute lazima iwe faili ya aina: :values.',
    'mimetypes' => 'Sehemu ya :attribute lazima iwe faili ya aina: :values.',
    'min' => [
        'array' => 'Sehemu ya :attribute lazima iwe na angalau vitu :min.',
        'file' => 'Sehemu ya :attribute lazima iwe angalau :min kilobytes.',
        'numeric' => 'Sehemu ya :attribute lazima iwe angalau :min.',
        'string' => 'Sehemu ya :attribute lazima iwe na angalau herufi :min.',
    ],
    'min_digits' => 'Sehemu ya :attribute lazima iwe na angalau tarakimu :min.',
    'missing' => 'Sehemu ya :attribute lazima isiwe.',
    'missing_if' => 'Sehemu ya :attribute lazima isiwe wakati :other ni :value.',
    'missing_unless' => 'Sehemu ya :attribute lazima isiwe isipokuwa :other ni :value.',
    'missing_with' => 'Sehemu ya :attribute lazima isiwe wakati :values ipo.',
    'missing_with_all' => 'Sehemu ya :attribute lazima isiwe wakati :values zipo.',
    'multiple_of' => 'Sehemu ya :attribute lazima iwe kizidishio cha :value.',
    'not_in' => ':attribute iliyochaguliwa si halali.',
    'not_regex' => 'Muundo wa sehemu ya :attribute si halali.',
    'numeric' => 'Sehemu ya :attribute lazima iwe nambari.',
    'password' => [
        'letters' => 'Sehemu ya :attribute lazima iwe na angalau herufi moja.',
        'mixed' => 'Sehemu ya :attribute lazima iwe na angalau herufi kubwa na ndogo moja.',
        'numbers' => 'Sehemu ya :attribute lazima iwe na angalau nambari moja.',
        'symbols' => 'Sehemu ya :attribute lazima iwe na angalau alama moja.',
        'uncompromised' => ':attribute iliyotolewa imeonekana katika uvujaji wa data. Tafadhali chagua :attribute tofauti.',
    ],
    'present' => 'Sehemu ya :attribute lazima iwepo.',
    'present_if' => 'Sehemu ya :attribute lazima iwepo wakati :other ni :value.',
    'present_unless' => 'Sehemu ya :attribute lazima iwepo isipokuwa :other ni :value.',
    'present_with' => 'Sehemu ya :attribute lazima iwepo wakati :values ipo.',
    'present_with_all' => 'Sehemu ya :attribute lazima iwepo wakati :values zipo.',
    'prohibited' => 'Sehemu ya :attribute imepigwa marufuku.',
    'prohibited_if' => 'Sehemu ya :attribute imepigwa marufuku wakati :other ni :value.',
    'prohibited_if_accepted' => 'Sehemu ya :attribute imepigwa marufuku wakati :other imekubaliwa.',
    'prohibited_if_declined' => 'Sehemu ya :attribute imepigwa marufuku wakati :other imekataliwa.',
    'prohibited_unless' => 'Sehemu ya :attribute imepigwa marufuku isipokuwa :other iko katika :values.',
    'prohibits' => 'Sehemu ya :attribute inapiga marufuku :other kuwa ipo.',
    'regex' => 'Muundo wa sehemu ya :attribute si halali.',
    'required' => 'Sehemu ya :attribute inahitajika.',
    'required_array_keys' => 'Sehemu ya :attribute lazima iwe na maingizo ya: :values.',
    'required_if' => 'Sehemu ya :attribute inahitajika wakati :other ni :value.',
    'required_if_accepted' => 'Sehemu ya :attribute inahitajika wakati :other imekubaliwa.',
    'required_if_declined' => 'Sehemu ya :attribute inahitajika wakati :other imekataliwa.',
    'required_unless' => 'Sehemu ya :attribute inahitajika isipokuwa :other iko katika :values.',
    'required_with' => 'Sehemu ya :attribute inahitajika wakati :values ipo.',
    'required_with_all' => 'Sehemu ya :attribute inahitajika wakati :values zipo.',
    'required_without' => 'Sehemu ya :attribute inahitajika wakati :values haipo.',
    'required_without_all' => 'Sehemu ya :attribute inahitajika wakati hakuna :values.',
    'same' => 'Sehemu ya :attribute lazima ifanane na :other.',
    'size' => [
        'array' => 'Sehemu ya :attribute lazima iwe na vitu :size.',
        'file' => 'Sehemu ya :attribute lazima iwe :size kilobytes.',
        'numeric' => 'Sehemu ya :attribute lazima iwe :size.',
        'string' => 'Sehemu ya :attribute lazima iwe na herufi :size.',
    ],
    'starts_with' => 'Sehemu ya :attribute lazima ianze na yafuatayo: :values.',
    'string' => 'Sehemu ya :attribute lazima iwe string.',
    'timezone' => 'Sehemu ya :attribute lazima iwe timezone halali.',
    'unique' => ':attribute tayari imechukuliwa.',
    'uploaded' => 'Sehemu ya :attribute imeshindwa kupakiwa.',
    'uppercase' => 'Sehemu ya :attribute lazima iwe uppercase.',
    'url' => 'Sehemu ya :attribute lazima iwe URL halali.',
    'ulid' => 'Sehemu ya :attribute lazima iwe ULID halali.',
    'uuid' => 'Sehemu ya :attribute lazima iwe UUID halali.',

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
            'rule-name' => 'ujumbe-wa-kibinafsi',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

