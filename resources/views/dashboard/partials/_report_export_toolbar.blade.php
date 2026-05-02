{{--
  Report Export Toolbar — PDF (browser print) + Excel export
  Usage: @include('dashboard.partials._report_export_toolbar', ['filename' => 'my-report'])
  Note: Hidden in print mode via @media print (so it won't appear in the PDF)
--}}
@php $exportFilename = $filename ?? 'report-' . date('Y-m-d'); @endphp

<link rel="stylesheet" media="screen" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
/* Show only on screen — hidden when printing */
.rpt-toolbar {
    position: fixed; bottom: 24px; right: 24px;
    display: none; gap: 10px; z-index: 99999;
}
@media screen {
    .rpt-toolbar { display: flex; }
}
@media print {
    .rpt-toolbar { display: none !important; }
}
.rpt-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 22px; border: none; border-radius: 10px;
    font-size: 14px; font-weight: 700; cursor: pointer;
    box-shadow: 0 4px 18px rgba(0,0,0,.22);
    transition: transform .15s, box-shadow .15s;
    font-family: Arial, sans-serif;
    text-decoration: none;
}
.rpt-btn:hover { transform: translateY(-2px); box-shadow: 0 7px 24px rgba(0,0,0,.3); }
.rpt-btn-excel { background: linear-gradient(135deg,#1b5e20,#2e7d32); color:#fff; }
.rpt-btn-pdf   { background: linear-gradient(135deg,#b71c1c,#c62828); color:#fff; }
</style>

<div class="rpt-toolbar" id="rptToolbar">
    <button class="rpt-btn rpt-btn-pdf" onclick="rptPdf()">
        <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF
    </button>
    <button class="rpt-btn rpt-btn-excel" onclick="rptExcel('{{ $exportFilename }}')">
        <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
    </button>
</div>

<script>
function rptPdf() {
    window.print();
}

function rptExcel(filename) {
    var table = document.querySelector('table.report-table')
             || (function(){
                    var best = null, max = 0;
                    document.querySelectorAll('table').forEach(function(t){
                        if (t.rows.length > max){ max = t.rows.length; best = t; }
                    });
                    return best;
                })();

    if (!table) { alert('No table found on this page.'); return; }

    var title = '';
    var h = document.querySelector('h1,h2,h3,.report-title,.box-title,.page-title,.report-header h2,.report-header h3');
    if (h) title = '<h2 style="font-family:Arial;color:#1a237e;margin-bottom:10px;">'
                 + h.textContent.trim() + '</h2>';

    var html = '<!DOCTYPE html><html xmlns:o="urn:schemas-microsoft-com:office:office" '
             + 'xmlns:x="urn:schemas-microsoft-com:office:excel">'
             + '<head><meta charset="UTF-8"/>'
             + '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>'
             + '<x:ExcelWorksheet><x:Name>Report</x:Name>'
             + '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>'
             + '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->'
             + '<style>td,th{border:1px solid #ccc;padding:6px 10px;font-size:12px;font-family:Arial;}'
             + 'th{background:#1a237e;color:#fff;font-weight:bold;}'
             + 'tr:nth-child(even) td{background:#f5f5f5;}</style>'
             + '</head><body>' + title + table.outerHTML + '</body></html>';

    var blob = new Blob(['﻿' + html], { type: 'application/vnd.ms-excel;charset=utf-8' });
    var url  = URL.createObjectURL(blob);
    var a    = document.createElement('a');
    a.href = url; a.download = filename + '.xls';
    document.body.appendChild(a); a.click();
    document.body.removeChild(a); URL.revokeObjectURL(url);
}
</script>
