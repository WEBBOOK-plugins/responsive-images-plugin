<?php

declare(strict_types=1);

return [
    'plugin' => [
        'name' => 'ResponsiveImages',
        'description' => 'Fügt srcset und sizes Attribute zu <img>-Tags hinzu',
        'author' => 'WebBook',
        'manage_settings' => 'Einstellungen für responsive Bilder',
        'manage_settings_permission' => 'Kann ResponsiveImages-Einstellungen verwalten',
    ],
    'settings' => [
        'tabs' => [
            'responsive_image' => 'Responsive Image',
            'focuspoint' => 'Focuspoint',
        ],

        'sections' => [
            'processing' => 'Verarbeitung',
            'processing_comment' => 'Einstellungen für die Verarbeitung der Bilder',

            'webp' => 'WebP',
            'webp_comment' => 'Einstellungen für die Konvertierung ins WebP Format',

            'html' => 'HTML',
            'html_comment' => 'Einstellungen zum HTML-Code',

            'focuspoint' => 'Fokuspunkt',
            'focuspoint_comment' => 'Einstellungen zum Fokuspunkt',
        ],

        'dimensions' => 'Generierte Bildgrössen',
        'dimensions_comment' => 'Komma-getrennte Liste mit zu erstellenden Bildbreiten (in px)',

        'allowed_extensions' => 'Beachtete Dateiendungen',
        'allowed_extensions_comment' => 'Komma-getrennte Liste mit Dateiendungen, die verarbeitet werden sollen',

        'webp_enabled' => 'Aktiviere WebP-Konvertierung',
        'webp_enabled_comment' => 'Bilder werden automatisch als WebP-Dateien an unterstütze Browser gesendet. Lies das README des Plugins bevor du diese Option aktivierst!',

        'webp_auto_convert' => 'Bilder automatisch konvertieren (nicht empfohlen)',
        'webp_auto_convert_comment' => 'Fügt eine Regeln in die .htaccess Datei hinzu, die Bilder beim ersten Aufruf automatisch ins WebP Format konvertiert. Es wird empfohlen den responsive-images:convert artisan Befehl in einem Cronjob anstelle dieser Option zu verwenden (siehe README).',

        'webp_partial' => [
            'title' => 'WebP Support',
            'text' => 'Diese Funktion unterstützt nur Apache + .htaccess. Alle anderen Server benötigen eine manuelle Konfiguration! Siehe',
        ],

        'latest_conversion' => 'Letzte Bildkonvertierung',
        'latest_conversion_comment' => 'Zeitpunkt der letzten Bildkonvertierung durch den Konsolenbefehl',

        'log_unprocessable' => 'Unverarbeitbare Bilder loggen',
        'log_unprocessable_comment' => 'Erstellt einen Logeintrag für jedes Bild, das nicht verarbeitet werden kann',

        'alternative_src' => 'src-Attribute (Komma-getrennt)',
        'alternative_src_comment' => 'Verwende als Bildquelle diese Attribute. Nützlich für Lazy-Loading-Plugins.',

        'alternative_src_set' => 'srcset-Attribut (Komma-getrennt)',
        'alternative_src_set_comment' => 'Füge die responsiven Bilder in diese Attribute ein. Nützlich für Lazy-Loading-Plugins. Jedes src-Attribut muss hier einen Partner haben (gleiche Anzahl)',

        'add_class' => 'class-Attribut',
        'add_class_comment' => 'Füge folgende Klasse zu jedem verarbeiteten Bild hinzu. Nützlich für die Verwendung mit CSS-Frameworks wie Bootstrap.',

        'focuspoint_class' => 'img class-Attribut',
        'focuspoint_class_comment' => 'Individuelle Klasse für Fokuspunkt-Bild  (Standard: .focuspoint-container)',

        'focuspoint_container_class' => 'container class-Attribut',
        'focuspoint_container_class_comment' => 'Klasse für Fokuspunkt-Container (Leer = kein Container, Standard: inaktiv)',

        'focuspoint_data_x' => 'data-Attribut für X-Achse',
        'focuspoint_data_x_comment' => 'Die X-Koordinate des Fokuspunktes wird in dieses Data-Attribut geschrieben (z. B. data-focus-x="40", Standard: inaktiv)',

        'focuspoint_data_y' => 'data-Attribut für Y-Achse',
        'focuspoint_data_y_comment' => 'Die Y-Koordinate des Fokuspunktes wird in dieses Data-Attribut geschrieben (z. B. data-focus-y="40", Standard: inaktiv)',

        'allow_inline_object' => 'Inline-CSS für object-fit und object-position',
        'allow_inline_object_comment' => 'Schreibe die object-* Regeln direkt als style ins HTML',

        'allow_inline_sizing' => 'Inline-CSS für Bildbreite und -höhe',
        'allow_inline_sizing_comment' => 'Schreibe die width und height Attribute direkt als style ins HTML',
    ],

    'fields' => [
        'focus_x_axis' => 'Fokuspunkt X-Achse',
        'focus_y_axis' => 'Fokuspunkt Y-Achse',
    ],

    'reportwidgets' => [
        'clearcache' => [
            'label' => 'Responsive Images: Cache leeren',
            'description' => 'Nutze untenstehenden Button um den Bilder-Cache zu leeren.',
            'button' => 'Bilder-Cache leeren',
            'confirm' => 'Bilder-Cache wirklich leeren?',
            'success' => 'Bilder-Cache geleert',
        ],
    ],
];
