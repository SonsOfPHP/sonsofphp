<?php

namespace SonsOfPHP\Bard\Manipulator;

use SonsOfPHP\Bard\JsonFile;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface JsonFileManipulatorInterface
{
    /**
     * @param JsonFile      $primary   For lack of a better word, this is going to be the
     *                                 json file we want to modify.
     * @param JsonFile|null $reference If provided, this is a file to use for reference to
     *                                 make updates to the primary
     *
     * @return JsonFile This MUST return a new JsonFile object. The returned JsonFile is
     *                  used as the new primary json file. If no changes need to be made,
     *                  you can just return the primary JsonFile that was passed in
     */
    public function apply(JsonFile $primary, ?JsonFile $reference = null): JsonFile;
}
