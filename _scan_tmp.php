<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$routeNames = collect(app('router')->getRoutes()->getRoutes())
    ->map(fn ($route) => $route->getName())
    ->filter()
    ->unique()
    ->values();

$roots = [__DIR__ . '/app', __DIR__ . '/resources/views', __DIR__ . '/routes'];
$files = [];
foreach ($roots as $root) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php'], true)) {
            $files[] = $file->getPathname();
        }
    }
}

$missing = [];
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match_all("/route\(\s*['\"]([^'\"]+)['\"]/", $content, $matches)) {
        foreach ($matches[1] as $name) {
            if (str_contains($name, '*') || str_contains($name, '$')) {
                continue;
            }
            if (!$routeNames->contains($name)) {
                $missing[$name][] = str_replace(__DIR__ . '/', '', $file);
            }
        }
    }
}

echo 'Route names: ' . $routeNames->count() . PHP_EOL;
echo 'Missing references: ' . count($missing) . PHP_EOL;
foreach ($missing as $name => $locations) {
    echo '- ' . $name . ' => ' . implode(', ', array_unique($locations)) . PHP_EOL;
}
