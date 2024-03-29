<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Surat Jalan</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            padding: 48px !important;
        }

        .content {
            background-color: white;
            font-size: 12px;
        }

        .underline {
            text-decoration: underline;
        }

        .bold {
            font-weight: 600;
        }

        .table-no-border {
            width: 100%;
        }

        .table-no-border tr th {
            padding: 4px 0;
        }

        .table-no-border tr td {
            padding: 1px 0;
        }

        .table-border {
            width: 100%;
        }

        .table-border tr th {
            padding: 8px 4px;
            background-color: #e7e7e7;
        }

        .table-border tr td {
            padding: 2px 4px;
            height: 24px;
            font-weight: bold;
        }

        .table-border,
        .table-border th,
        .table-border td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="content">
        {{-- Sesuaikan URL --}}
        @php
            $logoCV =
                'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo-madyotomo.png')));
            $judulCV =
                'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/title-madyotomo.png')));
        @endphp

        {{-- Kop Surat --}}
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: middle; text-align: center; position: relative; padding: 24px 0;">
                    <img src="{{ $logoCV }}" alt="" style="position: absolute; top:0; left: 0;"
                        width="190px">

                    <img src="{{ $judulCV }}" alt="" width="300px" style="text-align: center;">
                </td>
            </tr>

            <tr>
                <td style="padding-top: 36px; padding-left: 90px;">
                    <p>
                        Jl. Raya Solo-Purwodadi, Km. 26, Kp. Partoyasan, RT 05, Desa Soko, Kec. Miri, Sragen.
                        <br>
                        Telepon 08122626460 / 08562995018
                    </p>
                </td>
            </tr>
        </table>
        {{-- End Kop Surat --}}

        {{-- Isi Surat --}}
        <div>
            <p class="underline bold" style="width: 100%; text-align: center; margin: 16px 0; font-size: 18px;">SURAT
                JALAN</p>

            {{-- Isi Kepala Surat --}}
            <table class="table-no-border">
                <tr>
                    <td style="width: 100%;">
                        {{-- Kalau mau lebih gampang, ubah jadi component --}}
                        {{-- Component --}}
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Nomor</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->document_number }}
                                </td>
                            </tr>
                        </table>
                        {{-- Component --}}
                    </td>
                    <td style="min-width: 240px;">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Tanggal</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->entry_date }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Pelanggan</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->customer->name }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">No Telp</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->customer->phone }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Narahubung</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->customer->representative }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Nama Order</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->name }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Alamat</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->customer->address }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Keterangan</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {!! $record->note !!}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            {{-- End Isi Kepala Surat --}}
        </div>

        <div style="margin-top: 24px;">
            <p class="bold">
                Dengan hormat,
                <br>
                Bersama ini kami kirimkan sejumlah barang berikut:
            </p>

            <table class="table-border" style="margin: 12px 0;">
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th>Nama</th>
                    <th style="width: 150px;">Jumlah</th>
                </tr>
            </table>

            <table class="table-border" style="margin-bottom: 24px;">
                {{-- Perulangan data --}}
                @foreach ($deliveryItems as $item)
                    <tr style="width: 100%">
                        <td style="width: 40px;">{{ $index }}</td>
                        <td>{{ $item['product'] }}</td>
                        <td style="width: 150px;">{{ $item['quantity'] }}</td>
                    </tr>
                    @php
                        $index++;
                    @endphp
                @endforeach

                {{-- End Perulangan data --}}

                <tr>
                    <td></td>
                    <td>TOTAL:</td>
                    <td>{{ $total }}</td>
                </tr>
            </table>
        </div>
        {{-- End Isi Surat --}}

        {{-- Footer Surat --}}
        <div>
            <p class="bold" style="margin-bottom: 48px;">
                Mohon untuk diperiksa dan diterima.
            </p>

            <table style="width: 100%; padding: 0;">
                <tr style="font-weight: bold; text-align: center">
                    <td style="width: 100%; padding-bottom: 100px">
                        Diantar oleh,
                    </td>
                    <td style="width: 100%; padding-bottom: 100px">
                        Diterima oleh,
                    </td>
                    <td style="width: 100%; padding-bottom: 100px">
                        Hormat kami,
                    </td>
                </tr>
                <tr style="font-weight: bold; text-align: center">
                    <td style="width: 100%;">
                        (.................................................)
                    </td>
                    <td style="width: 100%;">
                        (.................................................)
                    </td>
                    <td style="width: 100%;">
                        (.................................................)
                    </td>
                </tr>
            </table>
        </div>
        {{-- End Footer Surat --}}
    </div>
</body>

</html>
