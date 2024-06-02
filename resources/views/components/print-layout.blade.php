<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .text-orange-500 {
            color: #431407;

        }

        @page {
            size: {{ $attributes->has('size') ? $attributes->get('size') : $size }};
            margin: 0;
        }

        body {
            margin: 0;
            min-width: initial !important
        }

        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            /*page-break-after: always*/
        }

        /** Paper sizes **/
        body.A3 .sheet {
            width: 297mm;
            height: 419mm
        }

        body.A3_landscape .sheet {
            width: 420mm;
            height: 296mm
        }

        body.A4 .sheet {
            width: 210mm;
            height: 296mm
        }

        body.A4_landscape .sheet {
            width: 297mm;
            height: 209mm
        }

        body.A5 .sheet {
            width: 148mm;
            height: 209mm
        }

        body.A5_landscape .sheet {
            width: 210mm;
            height: 147mm
        }

        body.card .sheet {
            width: 86mm;
            height: 54mm
        }

        /** Fix Chrome Issue **/
        @media print {
            body.A3 {
                width: 297mm
            }

            body.A3_landscape {
                width: 420mm
            }

            body.A4 {
                width: 210mm
            }

            body.A4_landscape {
                width: 297mm
            }

            body.A5 {
                width: 148mm
            }

            body.A5_landscape {
                width: 210mm
            }

            body.card {
                width: 86mm
            }
        }

        /** Padding area **/
        .sheet.padding-0mm {
            padding: 0mm
        }

        .sheet.padding-5mm {
            padding: 5mm
        }

        .sheet.padding-10mm {
            padding: 10mm
        }

        .sheet.padding-15mm {
            padding: 15mm
        }

        .sheet.padding-20mm {
            padding: 20mm
        }

        .sheet.padding-25mm {
            padding: 25mm
        }

        /** For screen preview **/
        @media screen {
            body {
                background: #e0e0e0
            }

            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);
                margin: 5mm;
            }
        }
    </style>
</head>
<body class="{{ $attributes->has('size') ? $attributes->get('size') : $size }}">
<main>
    {{ $content }}
</main>

@env('production')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
            window.afterprint = window.close;
            window.addEventListener("afterprint", () => self.close);
            window.onfocus = function () {
                setTimeout(function () {
                    window.close();
                }, 500);
            }
        })
    </script>
@endenv
</body>
</html>
