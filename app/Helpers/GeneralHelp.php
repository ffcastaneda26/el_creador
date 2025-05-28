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
    static public function normalize_text(string $original_text)
    {
        $search = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ñ'];
        $replace = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'N', 'n'];
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
    static public function spanish_date($date, string $m_format = 'n', string $d_format = 'n', $return_format = null)
    {

        if (!$date) {
            $date = now();
        }

        $day = GeneralHelp::spanish_day($date, $d_format);
        $month = GeneralHelp::spanish_month($date, $m_format);

        if ($return_format == 'dmy') {
            $day_name = GeneralHelp::spanish_day($date, 'l');
            return $day_name . ' ' . $date->format('d') . ' de ' . GeneralHelp::spanish_month($date, 'l') . ' del ' . $date->format('Y');
        }
        return $day . ' ' . ' de ' . $month . ' del ' . $date->format('Y');

    }


    /**
     * Regresa nombre del día en español
     * @param mixed $date
     * @param mixed $format: 's' = Corto  'l'= Largo
     * @return mixed
     */
    static public function spanish_day($date = null, $format = 'n')
    {
        $dias_corto = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'];
        $dias_largo = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        if (!$date) {
            $date = now();
        }
        if ($format == 'n')
            return $date->format('d');

        return $format == 's' ? $dias_corto[$date->format('w') - 1] : $dias_largo[$date->format('w') - 1];
    }

    /**
     * Regresa nombre del mes en español
     * @param mixed $date
     * @param mixed $format: 's' = Corto  'l'= Largo
     * @return mixed
     */
    static public function spanish_month($date = null, $format = 'n')
    {
        $meses_corto = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $meses_largo = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        if (!$date) {
            $date = now();
        }
        if ($format == 'n') {
            return $date->format('m');
        }

        return $format == 's' ? $meses_corto[$date->format('m') - 1] : $meses_largo[$date->format('m') - 1];


    }
    /**
     * Regresa el número convertido a letras
     * @return string
     */
    static public function number_to_letters($number, $decimals = 2, $text = null)
    {
        if (!$text) {
            $text = 'Pesos';
        }
        $converter = new NumerosALetras();
        // $converter->anexar = null;
        return ucwords(strtolower($converter->toInvoice($number, $decimals, $text))) . 'M/N';
    }


    /**
     * Regresa el número convertido a letras
     * @return string
     */
    /**
     * Regresa el número convertido a letras
     * @return string
     */
    static public function to_letters($number, $decimals = 2, $text = null)
    {
        if (!$text) {
            $text = 'Pesos';
        }
        $converter = new NumerosALetras();
        $converter->anexar = null;
        return ucwords(strtolower($converter->toInvoice($number, $decimals, $text))) . 'M/N';
    }

    static public function to_letters_whitout_text($number, $decimals = 2)
    {
        $converter = new NumerosALetras();
        $converter->anexar = null;
        return ucwords(strtolower($converter->toInvoice($number, $decimals)));
    }

    static public function to_letters_rounded($number)
    {
        $converter = new NumerosALetras();
        $converter->anexar = null;
        return ucwords(strtolower($converter->toLetters($number)));
    }
    /**
     * Convierte un número a letras solo con la parte entera y los decimales como fracción sobre 100
     * @param float $number
     * @param string|null $text
     * @return string
     */
    static public function number_to_letters_fraction($number, $text = null)
    {
        if (!$text) {
            $text = 'Pesos';
        }

        // Separar parte entera y decimal
        $integer_part = floor($number);
        $decimal_part = round(($number - $integer_part) * 100);

        // Convertir parte entera a letras
        $converter = new NumerosALetras();
        $converter->anexar = null;
        $integer_text = $converter->toInvoice($integer_part, 0, $text);

        // Formatear decimales como fracción
        $fraction_text = $decimal_part . '/100';

        return ucwords(strtolower($integer_text)) . ' ' . $fraction_text . ' M/N';
    }
}
