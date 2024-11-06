<?php

namespace App\Helpers;
use jmencoder\NumerosALetras\NumerosALetras;

class GeneralHelp
{
    /**
     * Sustituye carácteres acentuados por no acentuados asi también la Ñ
     * @param string $original_text
     * @return string
     */
    static public function normalize_text(string $original_text){
        $search = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','Ñ','ñ'];
        $replace = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','N','n'];
        return strtr($original_text, array_combine($search, $replace));
    }

    /**
     * Regresa la fecha en español
     * @param mixed $date
     * @param string $m_format
     * @param string $d_format
     * @return string
     * TODO:: Agregar el orden o formato de regreso
     */
    static public function spanish_date($date,string $m_format='n',string $d_format='n'){

        if(!$date){
            $date = now();
        }

        $day = GeneralHelp::spanish_day($date,$d_format);
        $month = GeneralHelp::spanish_month($date,$m_format);

        return $day . ' '. $date->format('d') . ' de ' . $month . ' del ' . $date->format('Y');

      }


    /**
     * Regresa nombre del día en español
     * @param mixed $date
     * @param mixed $format: 's' = Corto  'l'= Largo
     * @return mixed
     */
    static public function spanish_day($date=null,$format='n'){
        $dias_corto= ['Lun','Mar','Mie','Jue','Vie','Sab','Dom'];
        $dias_largo= ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
        if(!$date){
            $date = now();
        }
        if($format == 'n')      return $date->format('d');

        return $format == 's' ? $dias_corto[$date->format('w')-1] : $dias_largo[$date->format('w')-1];
    }

    /**
     * Regresa nombre del mes en español
     * @param mixed $date
     * @param mixed $format: 's' = Corto  'l'= Largo
     * @return mixed
     */
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

    /**
     * Regresa el número convertido a letras
     * @return string
     */
    static public function number_to_letters($number,$decimals=2,$text=null){
        if(!$text){
            $text = 'Pesos';
        }
        $converter = new NumerosALetras();
        $converter->anexar = null;
        return ucwords(strtolower($converter->toInvoice($number,$decimals,$text))) . 'M/N';

    }


}
