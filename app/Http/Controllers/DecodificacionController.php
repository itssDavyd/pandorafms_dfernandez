<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DecodificacionController extends Controller
{
    public function index()
    {
        // Fichero con problemas de codificación.
        $csv = "Patata,oi8,oo\nElMejor,oF8,Fo\nBoLiTa,0123456789,23\nAzul,01,01100\nOtRo,54?t,?4?\nManolita,kju2aq,u2ka\nPiMiEnTo,_-/.!#,#_";

        // Obtenemos nuestros valores de usuarios descodificados por su base.
        $decoded_scores = $this->decode_scores($csv);

        // Devolvemos resultados de ejercicio.
        echo "<h1>Ejercicio decodificación: </h1>
              <p>Ejercicio 1: Teniendo en cuenta toda esta información necesitamos que uses el
                sistema que quieras para implementar un código que muestre los resultados
                de las puntuaciones decodificadas de los usuarios.</p>";

        echo "<ul>";
        foreach ($decoded_scores as $username => $score) {
            echo "<li>" . $username . ", " . $score . "</li>";
        }
        echo "</ul>";
    }

    /**
     * Función para descodificar puntuaciones de un CSV de usuarios
     * @throws \Exception
     */
    public function decode_scores($csv): array
    {
        // Del csv obtenemos las lineas por separado.
        $lines = explode("\n", $csv);

        // Array a devolver con valores descodificados.
        $decoded_scores = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue; // Saltamos líneas vacías.

            // Dividimos cada linea del csv por la coma para obtener sus datos.
            list($username, $system_digits, $encoded_score) = explode(",", $line);

            // Descodificamos la puntuación por el sistema base que tengamos.
            $decoded_score = $this->decode_base($system_digits, $encoded_score);

            // Guardamos resultados en el array.
            $decoded_scores[$username] = $decoded_score;
        }
        return $decoded_scores;
    }

    /**
     * Función auxiliar para calcular por medio de la base de decodificación que tenemos.
     * @throws \Exception
     */
    public function decode_base($system_digits, $encoded_score): float|int
    {
        $base = strlen($system_digits); // La base se obtiene por el largo del string "oi8"=>base 3.

        // Valor de retorno
        $decimal_value = 0;

        // Le damos la vuelta para tener los dígitos de derecha a izquierda (0 a 10) asi facilita la conversión decimal.
        $encoded_score = strrev($encoded_score);

        for ($i = 0; $i < strlen($encoded_score); $i++) {
            $digit_value = strpos($system_digits, $encoded_score[$i]);

            // Si no tenemos la misma posición en nuestro sistema de dígitos que en las puntuaciones descodificadas entonces error y para ejecución.
            if ($digit_value === false) {
                throw new \Exception("Dígito no válido en la puntuación codificada.");
            }

            // de lo contrario multiplicamos el digito con coincidencia por la base elevada a la posición del digito (i)
            $decimal_value += $digit_value * pow($base, $i);
        }

        return $decimal_value;
    }
}
