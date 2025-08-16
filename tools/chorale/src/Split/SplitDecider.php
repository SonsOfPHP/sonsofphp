<?php

declare(strict_types=1);

namespace Chorale\Split;

use Chorale\State\StateStoreInterface;

final readonly class SplitDecider implements SplitDeciderInterface
{
    public function __construct(
        private readonly StateStoreInterface $state,
        private readonly ContentHasherInterface $hasher,
    ) {}

    public function reasonsToSplit(string $projectRoot, string $packagePath, array $options = []): array
    {
        if (!empty($options['force_split'])) {
            return ['forced'];
        }

        $reasons = [];
        $state   = $this->state->read($projectRoot);
        $ignore  = (array) ($options['ignore'] ?? []);
        $finger  = $this->hasher->hash($projectRoot, $packagePath, $ignore);

        $pkgState = (array) ($state['packages'][$packagePath] ?? []);
        $lastHash = (string) ($pkgState['fingerprint'] ?? '');
        if ($lastHash === '' || $lastHash !== $finger) {
            $reasons[] = 'content-changed';
        }

        if (!empty($options['verify_remote'])) {
            $repo   = (string) ($options['repo'] ?? '');
            $branch = (string) ($options['branch'] ?? 'main');
            $probe  = $this->probeRemote($repo, $branch);
            foreach ($probe as $r) {
                if (!in_array($r, $reasons, true)) {
                    $reasons[] = $r;
                }
            }
        }

        return [];
    }

    /** @return list<string> */
    private function probeRemote(string $repo, string $branch): array
    {
        if ($repo === '') {
            return [];
        }

        $refs = $this->lsRemote($repo, 'refs/heads/' . $branch);
        if ($refs === null) {
            return ['repo-unreachable'];
        }

        if ($refs === '') {
            return ['branch-missing'];
        }

        return [];
    }

    private function lsRemote(string $repo, string $ref): ?string
    {
        $cmd = sprintf('git ls-remote %s %s 2>&1', escapeshellarg($repo), escapeshellarg($ref));
        $out = [];
        $code = 0;
        @exec($cmd, $out, $code);
        if ($code !== 0) {
            return null;
        }

        return implode("\n", $out);
    }
}
