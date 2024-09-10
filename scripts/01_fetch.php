<?php
$basePath = dirname(__DIR__);

$meet1Path = $basePath . '/raw/meet1';
if (!file_exists($meet1Path)) {
    mkdir($meet1Path, 0777, true);
}

$page1Raw = $meet1Path . '/page.html';
if (!file_exists($page1Raw)) {
    file_put_contents($page1Raw, file_get_contents('https://udweb.tainan.gov.tw/News_Meeting.aspx?n=17119&sms=18033&page=1&PageSize=200'));
}

$c = file_get_contents($page1Raw);
$pos = strpos($c, '</thead><tbody>');
$posEnd = strpos($c, '</tbody>', $pos);
$lines = explode('</tr>', substr($c, $pos, $posEnd - $pos));
foreach ($lines as $line) {
    $cols = explode('</td>', $line);
    $cols[2] = trim(strip_tags($cols[2]));
    $parts = explode('/', $cols[2]);
    if (count($parts) != 3) {
        continue;
    }
    $parts[0] += 1911;
    $theDate = strtotime($parts[0] . '-' . $parts[1] . '-' . $parts[2]);
    $meet1File = $meet1Path . '/' . date('Ymd', $theDate) . '.pdf';
    if (!file_exists($meet1File)) {
        $parts = explode('"', $cols[0]);
        $cols[0] = $parts[9];
        $parts = explode('"', $cols[1]);
        $cols[1] = $parts[11];
        file_put_contents($meet1File, file_get_contents($cols[1]));
    }
}
