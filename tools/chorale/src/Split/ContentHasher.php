<?php

declare(strict_types=1);

namespace Chorale\Split;

final class ContentHasher implements ContentHasherInterface
{
    public function hash(string $projectRoot, string $packagePath, array $ignoreGlobs = []): string
    {
        $abs = rtrim($projectRoot, '/') . '/' . ltrim($packagePath, '/');
        if (!is_dir($abs)) {
            return hash('sha256', $abs . '|missing');
        }

        $files = $this->collectFiles($abs, $ignoreGlobs);
        sort($files);
        $h = hash_init('sha256');
        foreach ($files as $rel) {
            $full = $abs . '/' . $rel;
            hash_update($h, $rel . '|' . filesize($full) . '|');
            $data = @file_get_contents($full);
            if ($data !== false) {
                hash_update($h, $data);
            }
        }

        return hash_final($h);
    }

    /** @return list<string> relative file paths */
    private function collectFiles(string $absPackageDir, array $ignoreGlobs): array
    {
        $list = [];
        $iter = new \RecursiveIteratorIterator(
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator($absPackageDir, \FilesystemIterator::SKIP_DOTS),
                fn(\SplFileInfo $f, string $key, \RecursiveDirectoryIterator $it): bool => !($f->isDir() && $f->getFilename() === 'vendor')
            ),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($iter as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $rel = ltrim(substr((string) $file->getPathname(), strlen($absPackageDir)), '/');
            if ($this->isIgnored($rel, $ignoreGlobs)) {
                continue;
            }

            $list[] = $rel;
        }

        return $list;
    }

    private function isIgnored(string $relPath, array $globs): bool
    {
        foreach ($globs as $g) {
            if ($this->globMatch($g, $relPath)) {
                return true;
            }
        }

        return false;
    }

    private function globMatch(string $glob, string $path): bool
    {
        // Treat ** as .* and * as [^/]* for path components
        $re = $this->globToRegex($glob);
        return (bool) preg_match($re, $path);
    }

    private function globToRegex(string $glob): string
    {
        $g = str_replace('\\', '/', $glob);
        $g = ltrim($g, '/');
        $g = preg_quote($g, '#');
        // Undo quotes for wildcards and translate
        $g = str_replace(['\*\*', '\*', '\?'], ['\000DBLSTAR\000', '[^/]*', '[^/]'], $g);
        $g = str_replace('\000DBLSTAR\000', '.*', $g);
        return '#^' . $g . '$#u';
    }
}
