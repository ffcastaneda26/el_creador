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
	'accepted' => 'El campo :attribute debe ser aceptado.',
	'active_url' => 'El campo :attribute no es una URL válida.',
	'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
	'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
	'alpha' => 'El campo :attribute solo puede contener letras.',
	'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
	'alpha_num' => 'El campo :attribute solo puede contener letras y números.',
	'array' => 'El campo :attribute debe ser un array.',
	'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
	'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
	'between' => [
		'numeric' => 'El campo :attribute debe ser un valor entre :min y :max.',
		'file' => 'El archivo :attribute debe pesar entre :min y :max kilobytes.',
		'string' => 'El campo :attribute debe contener entre :min y :max caracteres.',
		'array' => 'El campo :attribute debe contener entre :min y :max elementos.',
	],
	'boolean' => 'El campo :attribute debe ser verdadero o falso.',
	'confirmed' => 'El campo confirmación de :attribute no coincide.',
	'date' => 'El campo :attribute no corresponde con una fecha válida.',
	'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
	'date_format' => 'El campo :attribute no corresponde con el formato de fecha :format.',
	'different' => 'Los campos :attribute y :other deben ser diferentes.',
	'digits' => 'El campo :attribute debe ser un número de :digits dígitos.',
	'digits_between' => 'El campo :attribute debe contener entre :min y :max dígitos.',
	'dimensions' => 'El campo :attribute tiene dimensiones de imagen inválidas.',
	'distinct' => 'El campo :attribute tiene un valor duplicado.',
	'email' => 'El campo :attribute debe ser una dirección de correo válida.',
	'ends_with' => 'El campo :attribute debe finalizar con alguno de los siguientes valores: :values',
	'exists' => 'El campo :attribute seleccionado no existe.',
	'file' => 'El campo :attribute debe ser un archivo.',
	'filled' => 'El campo :attribute debe tener un valor.',
	'gt' => [
		'numeric' => 'El campo :attribute debe ser mayor a :value.',
		'file' => 'El archivo :attribute debe pesar más de :value kilobytes.',
		'string' => 'El campo :attribute debe contener más de :value caracteres.',
		'array' => 'El campo :attribute debe contener más de :value elementos.',
	],
	'gte' => [
		'numeric' => 'El campo :attribute debe ser mayor o igual a :value.',
		'file' => 'El archivo :attribute debe pesar :value o más kilobytes.',
		'string' => 'El campo :attribute debe contener :value o más caracteres.',
		'array' => 'El campo :attribute debe contener :value o más elementos.',
	],
	'image' => 'El campo :attribute debe ser una imagen.',
	'in' => 'El campo :attribute es inválido.',
	'in_array' => 'El campo :attribute no existe en :other.',
	'integer' => 'El campo :attribute debe ser un número entero.',
	'ip' => 'El campo :attribute debe ser una dirección IP válida.',
	'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
	'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
	'json' => 'El campo :attribute debe ser una cadena de texto JSON válida.',
	'lt' => [
		'numeric' => 'El campo :attribute debe ser menor a :value.',
		'file' => 'El archivo :attribute debe pesar menos de :value kilobytes.',
		'string' => 'El campo :attribute debe contener menos de :value caracteres.',
		'array' => 'El campo :attribute debe contener menos de :value elementos.',
	],
	'lte' => [
		'numeric' => 'El campo :attribute debe ser menor o igual a :value.',
		'file' => 'El archivo :attribute debe pesar :value o menos kilobytes.',
		'string' => 'El campo :attribute debe contener :value o menos caracteres.',
		'array' => 'El campo :attribute debe contener :value o menos elementos.',
	],
	'max' => [
		'numeric' => 'El campo :attribute no debe ser mayor a :max.',
		'file' => 'El archivo :attribute no debe pesar más de :max kilobytes.',
		'string' => 'El campo :attribute no debe contener más de :max caracteres.',
		'array' => 'El campo :attribute no debe contener más de :max elementos.',
	],
	'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
	'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
	'min' => [
		'numeric' => 'El campo :attribute debe ser al menos :min.',
		'file' => 'El archivo :attribute debe pesar al menos :min kilobytes.',
		'string' => 'El campo :attribute debe contener al menos :min caracteres.',
		'array' => 'El campo :attribute debe contener al menos :min elementos.',
	],
	'not_in' => 'El campo :attribute seleccionado es inválido.',
	'not_regex' => 'El formato del campo :attribute es inválido.',
	'numeric' => 'El campo :attribute debe ser un número.',
	'password' => 'La contraseña es incorrecta.',
	'present' => 'El campo :attribute debe estar presente.',
	'regex' => 'El formato del campo :attribute es inválido.',
	'required' => 'El campo :attribute es obligatorio.',
	'required_if' => 'El campo :attribute es obligatorio cuando el campo :other es :value.',
	'required_unless' => 'El campo :attribute es requerido a menos que :other se encuentre en :values.',
	'required_with' => 'El campo :attribute es obligatorio cuando :values está presente.',
	'required_with_all' => 'El campo :attribute es obligatorio cuando :values están presentes.',
	'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente.',
	'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los campos :values están presentes.',
	'same' => 'Los campos :attribute y :other deben coincidir.',
	'size' => [
		'numeric' => 'El campo :attribute debe ser :size.',
		'file' => 'El archivo :attribute debe pesar :size kilobytes.',
		'string' => 'El campo :attribute debe contener :size caracteres.',
		'array' => 'El campo :attribute debe contener :size elementos.',
	],
	'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes valores: :values',
	'string' => 'El campo :attribute debe ser una cadena de caracteres.',
	'timezone' => 'El campo :attribute debe ser una zona horaria válida.',
	'unique' => 'El valor del campo :attribute ya está en uso.',
	'uploaded' => 'El campo :attribute no se pudo subir.',
	'url' => 'El formato del campo :attribute es inválido.',
	'uuid' => 'El campo :attribute debe ser un UUID válido.',

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

	'attributes' => [
        'short_spanish' => "Español Corto",
        'short_english' => "Inglés Corto",
		'short' => 'Corto',
		// Companies
		'name' => 'Nombre',
		'permitted_branches' => 'Sucursales Permitidas',
		'logo' => 'Logo',
		'image' => 'Imagen',
		'active' => 'Activo',
		'address' => 'Dirección',
		'country' => 'País',
		'phone' => 'Teléfono',
		'fax' => 'Fax',
		'email' => 'Correo Electrónico',
		'first_name' => 'Nombre',
		'middle_name' => 'Segundo Nombre',
		'last_name' => 'Paterno',
		'maternal_name' => 'Materno',
		'service' => 'Servicio',
		'key' => 'Clave',
		'type' => 'Tipo',
		'amount_type_id' => 'Importe',
		'role_id' => 'Rol',
		'company_id' => 'Empresa',
		'provider' => 'Proveedor',
// Clientes
		'dob' => 'F. Nac',
		'city' => 'Ciudad',
		'state_id' => 'Entidad Federativa',
		'zipcode' => 'Código Postal',
		'amount'                => 'Importe',
		'aproved'    => 'Aprobado',
		'areacode'    => 'Código de área',
		'authorization_code'    => 'Código Autorización',
		'authorization_id'    => 'Autorización',
		'authorizer_id'    => 'Autorizador',
		'balance'    => 'Saldo',
		'bank'    => 'Banco',
		'bank_id'    => 'Banco',
		'birthday'    => 'Fecha Nacimiento',
		'black_list'    => 'Lista negra',
		'card'    => 'Tarjeta',
		'cashier_id'    => 'Cajero',
		'code'    => 'Código',
		'company'    => 'Empresa',
		'country_id'    => 'País',
		'created_at'    => 'Creado(a)',
		'customer_id'    => 'Cliente',
		'date'    => 'Fecha',
		'days'    => 'Días',
		'description'    => 'Descripción',
		'email_verified_at'    => 'Correo Electrónico Verficiado el',
		'english'    => 'Inglés',
		'expire_at'    => 'Expira el',
		'failed_at'    => 'Fallo',
		'isdefault'    => '¿Predeterminado?',
		'key_mov'    => 'Clave Movimiento',
		'key_movement_id'    => 'Clave Movimiento',
		'message'    => 'Mensaje',
		'notes'    => 'Notas',
		'open'    => 'Abierto',
		'open_close_extra'    => 'Abrir/Cerrar Extra',
		'timezone'    => 'Zona Horaria',
		'token'    => 'Token',
		'total_charges'    => 'Total Cargos',
		'town'    => 'Población'
	]

];
