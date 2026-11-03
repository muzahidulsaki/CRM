<?php
$zip = new ZipArchive();
if ($zip->open('CRM_pr.xlsx') !== true) exit;
$ssXml = $zip->getFromName('xl/sharedStrings.xml');
$sharedStrings = [];
if ($ssXml) {
    $xml = simplexml_load_string($ssXml);
    foreach ($xml->si as $si) {
        $t = (string)$si->t;
        if (!$t && isset($si->r)) {
            $t = '';
            foreach ($si->r as $r) {
                $t .= (string)$r->t;
            }
        }
        $sharedStrings[] = $t;
    }
}

$sheetXml = $zip->getFromName('xl/worksheets/sheet2.xml');
if ($sheetXml) {
    $sx = simplexml_load_string($sheetXml);
    $rows = [];
    $serviceProviders = [];
    $whyNotDelivery = [];
    $count = 0;
    foreach ($sx->sheetData->row as $row) {
        $count++;
        if ($count == 1) continue;
        foreach ($row->c as $c) {
            $ref = (string)$c['r'];
            $col = preg_replace('/[0-9]/', '', $ref);
            $val = (string)$c->v;
            if ((string)$c['t'] === 's' && isset($sharedStrings[(int)$val])) {
                $val = $sharedStrings[(int)$val];
            }
            if ($col === 'J' && trim($val)) $serviceProviders[trim($val)] = true;
            if ($col === 'AC' && trim($val)) $whyNotDelivery[trim($val)] = true;
        }
    }
    echo "Service Providers found in Excel:\n";
    print_r(array_keys($serviceProviders));
    echo "\nWhy Not Delivery found in Excel:\n";
    print_r(array_keys($whyNotDelivery));
}
$zip->close();

