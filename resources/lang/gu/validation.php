<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validación del idioma
    |--------------------------------------------------------------------------
    |
        | Las siguientes líneas de idioma contienen los mensajes de error predeterminados utilizados por
        | La clase validadora. Algunas de estas reglas tienen múltiples versiones tales
        | como las reglas de tamaño. Siéntase libre de modificar cada uno de estos mensajes aquí.
    |
    */

    'accepted' => ':attribute સ્વીકારવું આવશ્યક છે.',
    'active_url' => ':attribute એ માન્ય URL નથી.',
    'after' => ':attribute :date પછીની તારીખ હોવી જોઈએ.',
    'after_or_equal' => ':attribute તારીખ :date પછીની અથવા તેની બરાબર હોવી જોઈએ.',
    'alpha' => ':attribute  માત્ર અક્ષરો હોઈ શકે છે.',
    'alpha_dash' => ':attribute માં માત્ર અક્ષરો, સંખ્યાઓ, ડેશ અને અન્ડરસ્કોર હોઈ શકે છે.',
    'alpha_num' => ':attribute માત્ર અક્ષરો અને સંખ્યાઓ હોઈ શકે છે.',
    'array' => ':attribute એરે હોવું આવશ્યક છે.',
    'before' => ':attribute :date પહેલાંની તારીખ હોવી જોઈએ.',
    'before_or_equal' => ':attribute તારીખ :date ની પહેલાની અથવા સમાન હોવી જોઈએ.',
    'વચ્ચે' => [
        'numeric' => ':attribute  :min અને :max વચ્ચે હોવી જોઈએ.',
        'file' => ':attribute  :min અને :max kilobytes ની વચ્ચે હોવી જોઈએ.',
        'string' => ':attribute  :min અને :max અક્ષરો વચ્ચે હોવી જોઈએ.',
        'array' => ':attribute :min અને :max આઇટમ્સ વચ્ચે હોવી જોઈએ.',
    ],
    'boolean' => ':attribute ફીલ્ડ સાચું કે ખોટું હોવું જોઈએ.',
    'confirmed' => ':attribute પુષ્ટિ મેળ ખાતી નથી.',
    'date' => ':attribute એ માન્ય તારીખ નથી.',
    'date_equals' => ':attribute તારીખ :date ની બરાબર હોવી જોઈએ.',
    'date_format' => ':attribute format :format સાથે મેળ ખાતી નથી.',
    'different' => ':attribute અને :other અલગ હોવા જોઈએ.',
    'digits' => ':attribute :digits અંકો હોવા જોઈએ.',
    'digits_between' => ':attribute :min અને :max dig ની વચ્ચે હોવી જોઈએ',
    'dimensions' => 'El campo :attribute no tiene una dimensión válida.',
    'distinct' => 'El campo :attribute tiene un valor duplicado.',
    'email' => 'El formato del :attribute es inválido.',
    'exists' => 'El campo :attribute seleccionado es inválido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute es requerido.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file' => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor que :value caracteres.',
        'array' => 'El campo :attribute puede tener hasta :value elementos.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser mayor o igual que :value.',
        'file' => 'El campo :attribute debe ser mayor o igual que :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor o igual que :value caracteres.',
        'array' => 'El campo :attribute puede tener :value elementos o más.',
    ],
    'image' => 'આ :attribute ઇમેજ હોવું આવશ્યક છે',
    'in' => 'El campo :attribute seleccionado es inválido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => 'El campo :attribute debe ser un entero.',
    'ip' => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El campo :attribute debe ser una cadena JSON válida.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :max.',
        'file' => 'El campo :attribute debe ser menor que :max kilobytes.',
        'string' => 'El campo :attribute debe ser menor que :max caracteres.',
        'array' => 'El campo :attribute puede tener hasta :max elementos.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser menor o igual que :max.',
        'file' => 'El campo :attribute debe ser menor o igual que :max kilobytes.',
        'string' => 'El campo :attribute debe ser menor o igual que :max caracteres.',
        'array' => 'El campo :attribute no puede tener más que :max elementos.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute debe ser menor que :max.',
        'file' => 'El campo :attribute debe ser menor que :max kilobytes.',
        'string' => 'El campo :attribute debe ser menor que :max caracteres.',
        'array' => 'El campo :attribute puede tener hasta :max elementos.',
    ],
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe tener al menos :min.',
        'file' => 'El campo :attribute debe tener al menos :min kilobytes.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'array' => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'not_in' => 'El campo :attribute seleccionado es invalido.',
    'not_regex' => 'El formato del campo :attribute es inválido.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'present' => 'El campo :attribute debe estar presente.',
    'regex' => 'El formato del campo :attribute es inválido.',
    'required' => 'આ :attribute ફીલ્ડ જરૂરી છે.',
    'required_if' => 'El campo :attribute es requerido cuando el campo :other es :value.',
    'required_unless' => 'El campo :attribute es requerido a menos que :other esté presente en :values.',
    'required_with' => 'El campo :attribute es requerido cuando :values está presente.',
    'required_with_all' => 'El campo :attribute es requerido cuando :values está presente.',
    'required_without' => 'El campo :attribute es requerido cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es requerido cuando ningún :values está presente.',
    'same' => 'El campo :attribute y :other debe coincidir.',
    'size' => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file' => 'El campo :attribute debe tener :size kilobytes.',
        'string' => 'El campo :attribute debe tener :size caracteres.',
        'array' => 'El campo :attribute debe contener :size elementos.',
    ],
    'starts_with' => 'El :attribute debe empezar con uno de los siguientes valores :values',
    'string' => 'El campo :attribute debe ser una cadena.',
    'timezone' => 'El campo :attribute debe ser una zona válida.',
    'unique' => 'આ :attribute પહેલેથી લેવામાં આવી છે.',
    'uploaded' => 'El campo :attribute no ha podido ser cargado.',
    'url' => 'El formato de :attribute es inválido.',
    'uuid' => 'El :attribute debe ser un UUID valido.',

    /*
    |--------------------------------------------------------------------------
    | Validación del idioma personalizado
    |--------------------------------------------------------------------------
    |
    |	Aquí puede especificar mensajes de validación personalizados para atributos utilizando el
    | convención "attribute.rule" para nombrar las líneas. Esto hace que sea rápido
    | especifique una línea de idioma personalizada específica para una regla de atributo dada.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atributos de validación personalizados
    |--------------------------------------------------------------------------
    |
        | Las siguientes líneas de idioma se utilizan para intercambiar los marcadores de posición de atributo.
        | con algo más fácil de leer, como la dirección de correo electrónico.
        | de "email". Esto simplemente nos ayuda a hacer los mensajes un poco más limpios.
    |
    */

    'attributes' => [
        'name' => 'નામ',
        'company' => 'કંપની',
        'designation' => 'હોદ્દો',
        'gender' => 'જાતિ',
        'age' => 'ઉંમર',
        'mobile_no' => 'મોબાઈલ નમ્બર',
        'whatsapp_no' => 'વોટ્સએપ નં',
        'aadhar_card_no' => 'આધાર કાર્ડ નં',
        'aadhar_card' => 'આધાર કાર્ડ',
        'pan_no' => 'પાન નં',
        'pan_card' => 'પાન કાર્ડ',
        'current_address' => 'વર્તમાન સરનામુ',
        'parmenant_address' => 'કાયમી સરનામુ',
        'salary' => 'પગાર',
        'da' => 'ડીએ',
        'nominee_name' => 'નોમિનીનું નામ',
        'nominee_relation' => 'નામાંકિત સંબંધ',
        'registration_no' => 'રજીસ્ટ્રેશન નં',
        'department_id_proof' => 'વિભાગીય ID પ્રૂફ',
        'saving_account_no' => 'બચત ખાતું નં',
        'place' => 'સ્થળ',
        'signature' => 'સહી',
        'witness_signature' => 'સાક્ષીની સહી',
        "email" => "ઈમેલ",
        "notification_email" => "સૂચના ઈમેલ",
        "Favicon" => "ફેવિકોન",
        "Site Title" => "સાઇટ શીર્ષક",
        "title" => "શીર્ષક",
        "Logo" => "લોગો",
        "Address" => "સરનામું",
        "Phone No" => "ફોન નંબર",
        'loan_interest' => 'લોન વ્યાજ',
        'monthly_saving' => 'માસિક_બચત',
        'share_amount' => 'શેર_રકમ',
        'bank_name' => 'બેંકનું નામ',
        'ifsc_code' =>'IFSC કોડ',
        'branch_address' =>'બ્રાંચનું સરનામું',
        'birthdate' =>'જન્મતારીખ',
        'account_type_id' => 'વિભાગ ખાતું',
        'user_id' => "વપરાશકર્તા",
        'month' => 'માસ',
        'rec_no' => 'રેક નંબર',
        'principal' => 'કિંમત',
        'interest' => 'વ્યાજ',
        'fixed' => 'નિયત માસિક બચત',
        'total_amount' => 'કુલ રકમ',
        'start_year' => 'પ્રારંભ વર્ષ',
        'end_year' => 'અંત વર્ષ',
        'start_month' => 'પ્રારંભ મહિનો',
        'end_month' => 'અંત મહિનો',
        'type_name' => 'નામ',
        'account_name' => 'ખાતાનું નામ',
        'ledger_group_id' => 'ખાતાવહી જૂથ',
        'department_name' => 'વિભાગનું નામ',
       


    ],
    'recaptcha' => 'La verificación de reCAPTCHA falló. Asegúrese de no ser un robot completando correctamente el desafío reCAPTCHA.',
];
