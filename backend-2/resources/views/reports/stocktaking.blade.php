<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stocktaking Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 0;
            padding: 20px 24px 40px;
        }

        /* ── Header ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .header-logo { display: table-cell; vertical-align: middle; width: 70px; }
        .header-logo img { height: 55px; }
        .header-text { display: table-cell; vertical-align: middle; text-align: center; }
        .header-text h2 { margin: 0; font-size: 16px; letter-spacing: 0.5px; }
        .header-text p  { margin: 2px 0 0; font-size: 10px; color: #555; }
        .header-date { display: table-cell; vertical-align: top; text-align: right; font-size: 10px; color: #555; width: 150px; }

        .divider { border: none; border-top: 1.5px solid #333; margin: 8px 0 12px; }

        /* ── Meta Grid ── */
        .meta-grid {
            display: table;
            width: 60%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }
        .meta-row { display: table-row; }
        .meta-label {
            display: table-cell;
            font-weight: bold;
            padding: 2px 8px 2px 0;
            white-space: nowrap;
            color: #444;
            width: 110px;
        }
        .meta-sep   { display: table-cell; padding: 2px 6px; color: #444; }
        .meta-value { display: table-cell; padding: 2px 0; }

        /* ── Section Title ── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 18px 0 5px;
            padding: 4px 8px;
            color: #000000ff;
            display: inline-block;
        }
        .section-count {
            font-size: 11px;
            font-weight: normal;
            color: #000000ff;
            margin-left: 6px;
        }

        /* ── Table ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        th {
            background: #555;
            color: #fff;
            padding: 5px 7px;
            text-align: left;
            font-size: 11px;
            border: 1px solid #444;
        }
        td {
            padding: 5px 7px;
            border: 1px solid #ccc;
            vertical-align: top;
            font-size: 11px;
        }
        tbody tr:nth-child(even) td { background: #f5f5f5; }

        .col-no  { width: 30px; text-align: center; }
        .col-uom { width: 45px; text-align: center; }
        .col-qty { width: 50px; text-align: right; }

        .muted { color: #777; font-size: 10px; }
        .empty-cell { text-align: center; color: #aaa; font-style: italic; padding: 10px; }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            font-size: 10px;
            color: #666;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }
    </style>
</head>
<body>

    @php
        $allItems = collect($report['items'] ?? []);
        $oldItems = $allItems->filter(fn($i) => empty($i['IS_NEW_ITEM']))->values();
        $newItems = $allItems->filter(fn($i) => !empty($i['IS_NEW_ITEM']))->values();
        $deletedItems = collect($report['deleted_items'] ?? [])->values();
    @endphp

    {{-- ── Header ── --}}
    <div class="header">
        <div class="header-logo">
            @if(!empty($report['logo_base64']))
                <img src="{{ $report['logo_base64'] }}" alt="Logo">
            @endif
        </div>
        <div class="header-text">
            <h2>STOCKTAKING REPORT</h2>
            <p>Laporan Hasil Penghitungan Stok Barang</p>
        </div>
        <div class="header-date">
            Dicetak:<br>{{ $report['generated_at'] ?? '-' }}
        </div>
    </div>

    <hr class="divider">

    {{-- ── Meta Info ── --}}
    <div class="meta-grid">
        <div class="meta-row">
            <span class="meta-label">Tim</span>
            <span class="meta-sep">:</span>
            <span class="meta-value">{{ $report['team']['kode_team'] ?? '-' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Penghitung 1</span>
            <span class="meta-sep">:</span>
            <span class="meta-value">{{ $report['team']['penghitung_1'] ?? '-' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Penghitung 2</span>
            <span class="meta-sep">:</span>
            <span class="meta-value">{{ $report['team']['penghitung_2'] ?? '-' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Lokasi</span>
            <span class="meta-sep">:</span>
            <span class="meta-value">{{ $report['lokasi'] ?? '-' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Tahun</span>
            <span class="meta-sep">:</span>
            <span class="meta-value">{{ $report['year'] ?? '-' }}</span>
        </div>
    </div>

    {{-- ── Tabel Barang Baru ── --}}
    <div class="section-title">
        Barang Baru
        <span class="section-count">({{ $newItems->count() }} item)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th class="col-uom">UOM</th>
                <th class="col-qty">Qty</th>
                <th>Logs</th>
            </tr>
        </thead>
        <tbody>
            @forelse($newItems as $index => $item)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td>{{ $item['ISITEAMITEMDETAIL_BRG_CODE'] ?? '-' }}</td>
                    <td>{{ $item['ISITEAMITEMDETAIL_BRG_NAME'] ?? '-' }}</td>
                    <td class="col-uom">{{ $item['ISITEAMITEMDETAIL_BRG_UOM'] ?? '-' }}</td>
                    <td class="col-qty">{{ $item['ISITEAMITEMDETAIL_QTY'] ?? '-' }}</td>
                    <td>
                        @if(!empty($item['logs']))
                            @foreach($item['logs'] as $log)
                                @php
                                    $rawQty = $log['LOG_NEWQTY'] ?? null;
                                    if ($rawQty === null) {
                                        $fmtQty = '-';
                                    } elseif (($item['IS_DECIMAL'] ?? 1) == 0) {
                                        $fmtQty = (string)(int) $rawQty;
                                    } else {
                                        $fmtQty = rtrim(rtrim(number_format((float)$rawQty, 3, ',', '.'), '0'), ',');
                                    }
                                @endphp
                                <div class="muted">{{ $log['DATE_CREATE'] ? date('d-m-Y', strtotime($log['DATE_CREATE'])) : '-' }} &mdash; {{ $fmtQty }}</div>
                            @endforeach
                        @else
                            <span class="muted">Tidak ada log</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-cell">Tidak ada barang baru</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── Tabel Barang Lama ── --}}
    <div class="section-title">
        Barang Terhapus
        <span class="section-count">({{ $deletedItems->count() }} item)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th class="col-uom">UOM</th>
                <th class="col-qty">Qty Sebelum</th>
                <th>Catatan</th>
                <th>Waktu Hapus</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deletedItems as $index => $item)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td>{{ $item['kode_plu'] ?? $item['kode_barcode'] ?? '-' }}</td>
                    <td>{{ $item['nama_barang'] ?? '-' }}</td>
                    <td class="col-uom">{{ $item['uom'] ?? '-' }}</td>
                    <td class="col-qty">{{ $item['qty_sebelumnya'] ?? '-' }}</td>
                    <td>{{ $item['note_sebelumnya'] ?? '-' }}</td>
                    <td>{{ !empty($item['deleted_at']) ? date('d-m-Y H:i', strtotime($item['deleted_at'])) : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-cell">Tidak ada barang terhapus</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">
        Barang Lama
        <span class="section-count">({{ $oldItems->count() }} item)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th class="col-uom">UOM</th>
                <th class="col-qty">Qty</th>
                <th>Logs</th>
            </tr>
        </thead>
        <tbody>
            @forelse($oldItems as $index => $item)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td>{{ $item['ISITEAMITEMDETAIL_BRG_CODE'] ?? '-' }}</td>
                    <td>{{ $item['ISITEAMITEMDETAIL_BRG_NAME'] ?? '-' }}</td>
                    <td class="col-uom">{{ $item['ISITEAMITEMDETAIL_BRG_UOM'] ?? '-' }}</td>
                    <td class="col-qty">{{ $item['ISITEAMITEMDETAIL_QTY'] ?? '-' }}</td>
                    <td>
                        @if(!empty($item['logs']))
                            @foreach($item['logs'] as $log)
                                @php
                                    $rawQty = $log['LOG_NEWQTY'] ?? null;
                                    if ($rawQty === null) {
                                        $fmtQty = '-';
                                    } elseif (($item['IS_DECIMAL'] ?? 1) == 0) {
                                        $fmtQty = (string)(int) $rawQty;
                                    } else {
                                        $fmtQty = rtrim(rtrim(number_format((float)$rawQty, 3, ',', '.'), '0'), ',');
                                    }
                                @endphp
                                <div class="muted">{{ $log['DATE_CREATE'] ? date('d-m-Y', strtotime($log['DATE_CREATE'])) : '-' }} &mdash; {{ $fmtQty }}</div>
                            @endforeach
                        @else
                            <span class="muted">Tidak ada log</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-cell">Tidak ada barang lama</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── Footer ── --}}
    <div class="footer">
        <span class="footer-left">Stocktaking Report &mdash; {{ $report['team']['kode_team'] ?? '-' }}</span>
        <span class="footer-right">{{ $report['generated_at'] ?? '-' }}</span>
    </div>

</body>
</html>
