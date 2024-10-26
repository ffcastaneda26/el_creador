<footer class="py-16 flex flex-between  text-sm text-black dark:text-white/70">
    <div class="flex items-center">
        <p class="text-sm text-gray-400">{{ __('All rights reserved') }} © 2024 Tecnicell Group.</p>
    </div>
    <div class="ml-auto flex items-center">
        @php

            use Carbon\Carbon;
            $date = Carbon::now();
            if (App::isLocale('en')) {
                $formattedDate = $date->format('l, F d, Y h:i A'); // Lunes, Enero 01, 2024 03:04 PM
            } else {
                $dias_semana_esp = array(
                    'Monday' => 'Lunes',
                    'Tuesday' => 'Martes',
                    'Wednesday' => 'Miércoles',
                    'Thursday' => 'Jueves',
                    'Friday' => 'Viernes',
                    'Saturday' => 'Sábado',
                    'Sunday' => 'Domingo'
                );
                
                $dia = date('l');
                $dia_semana_esp = $dias_semana_esp[$dia];
                $mes = $numero_mes = date('m');
                dd($dia_semana_esp,$mes);
                $formattedDate = $date->format('l, d F, Y H:i'); // Lunes, 01 Enero, 2024 15:04
            }
       @endphp
        <p class="text-sm text-gray-400">{{ __('Today is') .':' . $formattedDate }}</p>
    </div>
</footer>