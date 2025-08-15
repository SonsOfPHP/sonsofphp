<?php

declare(strict_types=1);

namespace Chorale\Discovery;

use Chorale\Util\PathUtilsInterface;

final class PackageScanner implements PackageScannerInterface
{
    public function __construct(
        private readonly PathUtilsInterface $paths
    ) {}

    public function scan(string $projectRoot, string $baseDir, array $paths = []): array
    {
        $root = rtrim($projectRoot, '/');
        $base = $root . '/' . $this->paths->normalize($baseDir);
        if (!is_dir($base)) {
            return [];
        }

        if ($paths !== []) {
            $out = [];
            foreach ($paths as $p) {
                $rel  = ltrim($p, './');
                // must be under baseDir
                if (!str_starts_with($this->paths->normalize($rel), $this->paths->normalize($baseDir) . '/')
                    && $this->paths->normalize($rel) !== $this->paths->normalize($baseDir)) {
                    continue;
                }
                $full = $root . '/' . $rel;

                // ignore any path that is (or is inside) vendor/
                if ($this->isInVendor($rel)) {
                    continue;
                }

                if (is_dir($full) && is_file($full . '/composer.json')) {
                    $out[] = $this->paths->normalize($rel);
                }
            }
            $out = array_values(array_unique($out));
            sort($out);
            return $out;
        }

        // Default: recursively scan $base, but never descend into any vendor/ directory
        $dirIter = new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS);
        $filter  = new \RecursiveCallbackFilterIterator(
            $dirIter,
            function (\SplFileInfo $file, string $key, \RecursiveDirectoryIterator $iterator): bool {
                if ($file->isDir()) {
                    // Do not descend into vendor directories anywhere under src/
                    return $file->getFilename() !== 'vendor';
                }
                // Files are irrelevant for traversal (we only care about dirs)
                return false;
            }
        );
        $it = new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::SELF_FIRST);

        $candidates = [];
        foreach ($it as $dir) {
            if (!$dir->isDir()) {
                continue;
            }
            $path = $dir->getPathname();
            $rel  = substr($path, strlen($root) + 1);

            // Quick guard against vendor/ in case a user passes a weird path
            if ($this->isInVendor($rel)) {
                continue;
            }

            // Only treat a directory as a package if it contains composer.json
            if (is_file($path . '/composer.json')) {
                $candidates[] = $this->paths->normalize($rel);
                // No need to look deeper inside this package for more composer.json files
                // (but iterator will continue along sibling branches)
            }
        }

        $candidates = array_values(array_unique($candidates));
        sort($candidates);
        return $candidates;
    }

    private function isInVendor(string $relativePath): bool
    {
        // Normalize separators and check any path segment equals 'vendor'
        $p = $this->paths->normalize($relativePath);
        $segments = explode('/', $p);
        return in_array('vendor', $segments, true);
    }
}
