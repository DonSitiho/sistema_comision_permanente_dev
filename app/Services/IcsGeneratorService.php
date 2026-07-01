<?php
// app/Services/IcsGeneratorService.php
namespace App\Services;
class IcsGeneratorService
{
    public static function generar(
        string $titulo,
        string $descripcion,
        \DateTimeInterface $inicio,
        ?\DateTimeInterface $fin = null,
        ?string $lugar = null
        ): string {
        
        $fin = $fin ?? $inicio->modify("+1 hour");
        $uid = uniqid("scpc-", true);
        $lines = [
            "BEGIN:VCALENDAR",
            "VERSION:2.0",
            "PRODID:-//SCPC//Michoacan//ES",
            "BEGIN:VEVENT",
            "UID:{$uid}",
            "DTSTAMP:" . gmdate("Ymd\THis\Z"),
            "DTSTART:" . $inicio->format("Ymd\THis"),
            "DTEND:" . $fin->format("Ymd\THis"),
            "SUMMARY:" . self::escapar($titulo),
            "DESCRIPTION:" . self::escapar($descripcion),
        ];
        if ($lugar) {
            $lines[] = "LOCATION:" . self::escapar($lugar);
        }
        
        $lines[] = "END:VEVENT";
        $lines[] = "END:VCALENDAR";
        return implode("\r\n", $lines);
    }
    private static function escapar(string $texto): string
    {
        return str_replace(
            ["\\\\", ",", ";", "\\n"],
            ["\\\\\\\\", "\\\\,", "\\\\;", "\\\\n"],
            $texto
        );
    }
}