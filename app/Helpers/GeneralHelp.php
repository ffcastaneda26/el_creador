<?php

namespace App\Helpers;

class GeneralHelp
{


    static public function normalize_text(string $original_text){
        $search = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','Ñ','ñ'];
        $replace = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','N','n'];
        return strtr($original_text, array_combine($search, $replace));
    }

    static public function spanish_date($date,string $format_month='short',string $format_day='num'){
        $fechaObj = new \DateTime();
        if($format_month == 'short'){
            $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
        }else{
            $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        }

        $mes = $meses[$date->format('m') - 1];
        $añoActual = $date->format('Y');

    }
    static public function spanish_day($date=null,$format='n'){
        $dias_corto= ['Lun','Mar','Mie','Jue','Vie','Sab','Dom'];
        $dias_largo= ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
        if(!$date){
            $date = now();
        }
        if($format == 'n')      return $date->format('d');

        return $format == 's' ? $dias_corto[$date->format('w')-1] : $dias_largo[$date->format('w')-1];
    }

    static public function spanish_month($date=null,$format='n'){
        $meses_corto = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $meses_largo = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        if(!$date){
            $date = now();
        }
        if($format == 'n'){
            return  $date->format('m');
        }

        return $format == 's' ? $meses_corto[$date->format('m')-1] : $meses_largo[$date->format('m')-1];


    }
}
