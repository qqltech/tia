@php
@endphp


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>DOT MATRIX</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            position: relative;
            padding-left: 15px;
        }

        ul li::before {
            content: "-";
            position: absolute;
            left: 0;
        }

        @page {
            margin: 0;
        }

        body {
            margin: 0;
        }

        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }

        /** Paper sizes **/
        body.A3 .sheet {
            width: 297mm;
            height: 419mm;
        }

        body.A3.landscape .sheet {
            width: 420mm;
            height: 296mm;
        }

        body.A4 .sheet {
            width: 210mm;
            height: 296mm;
        }

        body.A4.landscape .sheet {
            width: 297mm;
            height: 209mm;
        }

        body.A5 .sheet {
            width: 148mm;
            height: 209mm;
        }

        body.A5.landscape .sheet {
            width: 210mm;
            height: 147mm;
        }

        body.continuous_form .sheet {
            width: 107mm;
            height: 137.5mm;
        }

        body.continuous_form.landscape .sheet {
            width: 137.5mm;
            height: 107mm;
        }

        /** Padding area **/
        .sheet.padding-3mm {
            padding: 3mm;
        }

        .sheet.padding-5mm {
            padding: 5mm;
        }

        .sheet.padding-7mm {
            padding: 7mm;
        }
        .sheet.padding-10mm {
            padding: 10mm;
        }

        .sheet.padding-15mm {
            padding: 15mm;
        }

        .sheet.padding-20mm {
            padding: 20mm;
        }

        .sheet.padding-25mm {
            padding: 25mm;
        }

        /** For screen preview **/
        @media screen {
            body {
                background: #e0e0e0;
            }

            .sheet {
                background: white;
                box-shadow: 0 0.5mm 2mm rgba(0, 0, 0, 0.3);
                margin: 5mm;
            }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.A3.landscape {
                width: 420mm;
            }

            body.A3,
            body.A4.landscape {
                width: 297mm;
            }

            body.A4,
            body.A5.landscape {
                width: 210mm;
            }

            body.A5 {
                width: 148mm;
            }
        }

        @page {
            size: continuous_form;
        }

        table {
            border-collapse: collapse;
        }

        table,
        td,
        th {
            /* border: 0.5px solid black; */
            padding-right: 0px;
        }

        tr {
            height: 20px;
        }

        table {
            table-layout: fixed;
            font-size: 15px;
            font-family: 'Courier New', monospace;
            /* font-family: sans-serif; */
            /*  font-family: monospace;  */
            /* font-family: monako; */
            /* font-family: arial; */
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="continuous_form">
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-3mm">
        <!-- Write HTML just like a web page -->
        <!-- <article>This is an Continuous Form document.</article> -->

        <table style="table-layout: fixed; width: 100%">
            <thead>
                <tr><td style="height:20px" colspan="30"></td></tr>
                <tr>
                    <td colspan="13" style="font-weight:bold; text-size: 16px">TIA</td>
                    <td colspan="10" style="font-weight:bold; text-size: 16px">DOT MATRIX</td>
                    <td style="text-align: right; font-weight:bold; text-size: 16px" colspan="6">{{date('d F Y');}}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold; text-size: 16px">Column</td>
                    <td colspan="6" style="font-weight:bold; text-size: 16px">Lorem ipsum</td>
                    <td colspan="11"></td>
                    <td colspan="5" style="font-weight:bold; text-size: 16px">Column</td>
                    <td style="text-align: right; font-weight:bold; text-size: 16px" colspan="4">Lorem ipsum</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold; text-size: 16px">Column</td>
                    <td colspan="6" style="font-weight:bold; text-size: 16px">Lorem ipsum</td>
                    <td colspan="11"></td>
                    <td colspan="5" style="font-weight:bold; text-size: 16px">Column</td>
                    <td style="text-align: right; font-weight:bold; text-size: 16px" colspan="4">Lorem ipsum</td>
                </tr>
                
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight:bold; text-size: 16px; font-size:14px" colspan="9">No. Matrix :  MATRIX-0001</td>
                    <td colspan="11"></td>
                    <td style="font-weight:bold; text-size: 16px" colspan="5">Column</td>
                    <td style="text-align: right; font-weight:bold; text-size: 16px" colspan="4">Lorem ipsum</td>
                </tr>
                </tr>
                <tr style="text-align: center;font-weight:bold; font-size: 14px">
                    <td style="border: 0.5px solid black; text-align: center;">No</td>
                    <td style="border: 0.5px solid black; text-align: center; " colspan="4">Column 1</td>
                    <td style="border: 0.5px solid black; text-align: center;" colspan="9">Column 2</td>
                    <td style="border: 0.5px solid black; text-align: center;" colspan="9">Column 3</td>
                    <td style="border: 0.5px solid black; text-align: center;" colspan="6">Column 4</td>
                </tr>
                <tr style="font-size:15px">
                    <td style="font-weight:bold;text-align: center;">1</td>
                    <td style="font-weight:bold;text-align: left; font-size: 14px !important" colspan="4">Lorem ipsum</td>
                    <td style="font-weight:bold;text-align: left;" colspan="9">Lorem ipsum</td>
                    <td style="font-weight:bold;text-align: right;" colspan="9">Lorem ipsum</td>
                    <td style="font-weight:bold;text-align: right;" colspan="6">Lorem ipsum</td>
                </tr>
                <tr>
                    <td colspan="10"></td>
                </tr>
            </tbody>
        </table>
    </section>
</body>

</html>
