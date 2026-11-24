<?php
// Vargus kunstimuuseumis – tehniline katse: väga lihtne mängutoa server.
// Hoiab iga mängutoa seisu failis data/room_KOOD.json. Vajab PHP 7.4+.
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

const PAINTINGS = ['portree' => 'portree', 'monet' => 'maastik', 'caillebotte' => 'natyyrmort', 'paevalilled' => 'natyyrmort'];
const FRAMES = ['F1' => 'portree', 'F2' => 'maastik', 'F3' => 'natyyrmort'];

function fresh(): array {
    $p = [];
    foreach (PAINTINGS as $k => $_) { $p[$k] = 'depot'; }
    return ['v' => 1, 'p' => $p, 'hl' => null, 'open' => false, 'log' => [], 'seen' => ['vr' => 0, 'team' => 0]];
}
function out($data, int $code = 200): void { http_response_code($code); echo json_encode($data, JSON_UNESCAPED_UNICODE); exit; }

$room = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)($_GET['room'] ?? '')));
if ($room === '' || strlen($room) > 10) { out(['error' => 'room'], 400); }
$who = (($_GET['who'] ?? '') === 'vr') ? 'vr' : 'team';

$dir = __DIR__ . '/data';
if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
$fp = @fopen("$dir/room_$room.json", 'c+');
if (!$fp) { out(['error' => 'storage'], 500); }
flock($fp, LOCK_EX);
$raw = stream_get_contents($fp);
$s = $raw ? json_decode($raw, true) : null;
if (!is_array($s) || !isset($s['p'])) { $s = fresh(); }

$write = false;
$now = time();
if (($s['seen'][$who] ?? 0) < $now - 1) { $s['seen'][$who] = $now; $write = true; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $in = json_decode((string)file_get_contents('php://input'), true);
    if (!is_array($in)) { $in = []; }
    $a = (string)($in['a'] ?? '');
    $pk = (string)($in['p'] ?? '');
    $fk = (string)($in['f'] ?? '');
    $hasP = array_key_exists($pk, PAINTINGS);
    $hasF = array_key_exists($fk, FRAMES);
    switch ($a) {
        case 'take':
            if ($hasP) {
                foreach ($s['p'] as $k => $loc) { if ($loc === 'held') { $s['p'][$k] = 'depot'; } }
                $s['p'][$pk] = 'held';
            }
            break;
        case 'hang':
            if ($hasP && $hasF) {
                foreach ($s['p'] as $k => $loc) { if ($loc === "f:$fk" && $k !== $pk) { $s['p'][$k] = 'depot'; } }
                $s['p'][$pk] = "f:$fk";
                if (($s['hl'] ?? null) === $fk) { $s['hl'] = null; }
            }
            break;
        case 'drop':
            if ($hasP) { $s['p'][$pk] = 'depot'; }
            break;
        case 'hl':
            $s['hl'] = $hasF ? $fk : null;
            break;
        case 'reset':
            $seen = $s['seen'];
            $s = fresh();
            $s['seen'] = $seen;
            break;
    }
    $open = true;
    foreach (FRAMES as $f => $g) {
        $ok = false;
        foreach ($s['p'] as $k => $loc) { if ($loc === "f:$f" && PAINTINGS[$k] === $g) { $ok = true; } }
        if (!$ok) { $open = false; }
    }
    $s['open'] = $open;
    $s['v'] = (int)$s['v'] + 1;
    $s['log'][] = ['t' => $now, 'a' => $a, 'p' => $pk, 'f' => $fk, 'who' => $who];
    $s['log'] = array_slice($s['log'], -30);
    $write = true;
}

if ($write) {
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($s, JSON_UNESCAPED_UNICODE));
    fflush($fp);
}
flock($fp, LOCK_UN);
fclose($fp);
$s['now'] = $now;
out($s);
