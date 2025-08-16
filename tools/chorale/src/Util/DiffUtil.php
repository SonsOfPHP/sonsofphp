<?php

declare(strict_types=1);

namespace Chorale\Util;

final class DiffUtil implements DiffUtilInterface
{
    public function changed(array $current, array $desired, array $keys): bool
    {
        foreach ($keys as $k) {
            $a = $current[$k] ?? null;
            $b = $desired[$k] ?? null;
            if (!$this->equalsNormalized($a, $b)) {
                return true;
            }
        }

        return false;
    }

    private function equalsNormalized(mixed $a, mixed $b): bool
    {
        if (is_array($a) && is_array($b)) {
            return $this->equalArray($a, $b);
        }

        return $a === $b;
    }

    private function equalArray(array $a, array $b): bool
    {
        if ($this->isAssoc($a) || $this->isAssoc($b)) {
            ksort($a);
            ksort($b);
            if (count($a) !== count($b)) {
                return false;
            }

            foreach ($a as $k => $v) {
                if (!array_key_exists($k, $b)) {
                    return false;
                }

                if (!$this->equalsNormalized($v, $b[$k])) {
                    return false;
                }
            }

            return true;
        }

        // list arrays: compare values irrespective of order
        sort($a);
        sort($b);
        if (count($a) !== count($b)) {
            return false;
        }

        foreach ($a as $i => $v) {
            if (!$this->equalsNormalized($v, $b[$i])) {
                return false;
            }
        }

        return true;
    }

    private function isAssoc(array $arr): bool
    {
        if ($arr === []) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
