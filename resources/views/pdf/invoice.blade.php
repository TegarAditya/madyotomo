<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Document</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 48px !important;
        }

        .content {
            background-color: white;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
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

        .form-ttd {
            width: 100%;
            height: 100px;
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
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/title_madyotomo.png')));
        @endphp

        {{-- Kop Surat --}}
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: middle; text-align: center; position: relative; padding: 24px 0;">
                    <img src="{{ $logoCV }}" alt="" style="position: absolute; top:0; left: 0;" width="190px">

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
            <p class="underline bold" style="width: 100%; text-align: center; margin: 16px 0; font-size: 18px;">FAKTUR
                TAGIHAN</p>

            {{-- Isi Kepala Surat --}}
            <table class="table-no-border">
                <tr>
                    <td style="width: 100%;">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Nomor</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->document_number }}
                                </td>
                            </tr>
                        </table>
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
                    <td style="width: 100%;">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Nomor Order</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->document_number }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="min-width: 240px;">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">No. Bukti</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->proof_number }}
                                </td>
                            </tr>
                        </table>
                    </td>
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
                                    0271-2878721
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;"></td>
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
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Alamat</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    {{ $record->order->customer->address }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="table-no-border" style="margin-top: 24px;">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;" class="bold">Spesifikasi
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 100%;">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Jenis Order</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    Cetak dan Potong
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="min-width: 240px;">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Konfigurasi</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    4/0
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="min-width: 240px;">
                        <table>
                            <tr>
                                <td style="border: 0; vertical-align: top; min-width: 80px;">Ongkos Cetak</td>
                                <td style="border: 0; vertical-align: top;">:</td>
                                <td style="border: 0; width: 100%; padding-left: 8px; vertical-align: top;">
                                    Rp {{ $record->price }} / druk
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
            </table>
            {{-- End Isi Kepala Surat --}}
        </div>

        <div>
            <table class="table-border" style="margin: 12px 0;">
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th>Produk</th>
                    <th style="width: 150px;">Oplah</th>
                    <th style="width: 150px;">Tagihan</th>
                </tr>
            </table>

            <table class="table-border" style="margin-bottom: 24px;">
                {{-- Perulangan data --}}
                @foreach ($invoiceItems as $item)
                    <tr style="width: 100%">
                        <td style="width: 40px;">{{ $index }}</td>
                        <td>{{ $item['product'] }}</td>
                        <td style="width: 150px; text-align: right;">{{ $item['quantity'] }}</td>
                        <td style="width: 150px; text-align: right;">{{ $item['price'] }}</td>
                    </tr>
                    @php
                        $index++;
                    @endphp
                @endforeach
                {{-- End Perulangan data --}}

                <tr>
                    <td></td>
                    <td>TOTAL:</td>
                    <td style="text-align: right;">{{ $total['quantity'] }}</td>
                    <td style="text-align: right;">{{ $total['price'] }}</td>
                </tr>
            </table>
        </div>
        {{-- End Isi Surat --}}

        {{-- Footer Surat --}}
        <div>
            <table style="width: 100%; padding: 0;">
                <tr style="font-weight: bold;">
                    <td style="width: 100%; vertical-align: top;">
                        Diterima oleh,
                    </td>
                    <td style="vertical-align: top; width: 100%; text-align: center">
                        <div style="float: right;">
                            <div class="bold">
                                <p>Dibuat oleh,</p>
                            </div>

                            {{-- Jarak TTD atur disini --}}
                            <div style="height: 80px;"></div>
                            {{-- Jarak TTD atur disini --}}

                            <div>
                                <p class="bold">
                                    Ringgo Ismoyo Buwono
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <div style="clear: both;"></div>
        </div>
        {{-- End Footer Surat --}}
    </div>
</body>

</html>