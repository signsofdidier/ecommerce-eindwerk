<?php
$exportDir = 'C:\Users\Didie\Desktop\project_export';

// Map aanmaken als die nog niet bestaat
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0777, true);
}

$dir = __DIR__;

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($rii as $file) {
    if ($file->isDir()) continue;

    $ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
    if (in_array($ext, ['php', 'html', 'js', 'css'])) {
        $content = file_get_contents($file->getPathname());

        // Bestandspad herschrijven naar geldige bestandsnaam
        $relativePath = str_replace(['\\', '/'], '_', substr($file->getPathname(), strlen($dir) + 1));
        $outputFile = $exportDir . '\\' . $relativePath . '.txt';

        file_put_contents($outputFile, $content);
    }
}

echo "✅ Projectbestanden geëxporteerd naar: $exportDir\n";
