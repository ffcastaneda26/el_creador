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
                $dias_esp_largo = array(
                    'Monday' => 'Lunes',
                    'Tuesday' => 'Martes',
                    'Wednesday' => 'Miércoles',
                    'Thursday' => 'Jueves',
                    'Friday' => 'Viernes',
                    'Saturday' => 'Sábado',
                    'Sunday' => 'Domingo'
                );
                $dias_eso_corto = array(
                    'Monday' => 'Lun',
                    'Tuesday' => 'Mar',
                    'Wednesday' => 'Mie',
                    'Thursday' => 'Jue',
                    'Friday' => 'Vie',
                    'Saturday' => 'Sab',
                    'Sunday' => 'Dom'
                );
                $meses_esp_largo = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                $meses_esp_corto = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                $dia = date('l');
                $mes = date('m');
                $nombre_mes = $meses_esp_largo[$mes-1];
                $nombre_dia = $dias_esp_largo[$dia];
                $dia = $date->format('d');

                $formattedDate =  $nombre_dia . ' ' . $dia . ' de ' . $nombre_mes . ' de ' .$date->format('Y') . ' ' . $date->format('h:i A');
            }
       @endphp
        <p class="text-sm text-gray-400">{{ __('Today is') .':' . $formattedDate }}</p>
    </div>
</footer>
