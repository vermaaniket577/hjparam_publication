<?php
$content = file_get_contents('resources/views/journals/show.blade.php');
$directives = ['if', 'foreach', 'forelse', 'auth', 'guest', 'section'];
foreach ($directives as $d) {
    $count = substr_count($content, '@' . $d);
    $endCount = substr_count($content, '@end' . $d);
    if ($count != $endCount) {
        echo "Mismatch in @$d: $count start vs $endCount end\n";
    }
}
