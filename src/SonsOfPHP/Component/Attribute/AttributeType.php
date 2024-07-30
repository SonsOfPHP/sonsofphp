<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Attribute;

use SonsOfPHP\Contract\Attribute\AttributeTypeInterface;

enum AttributeType: string implements AttributeTypeInterface
{
    case TYPE_TEXT = 'text';
    // -- OR --
    case TextType = 'text';
    // -- OR --
    case Text = 'text';

    //case TextareaType   = 'textarea';
    //case CheckboxType = 'checkbook';
    //case IntegerType  = 'integer';
    //case FloatType    = 'float';
    //case DatetimeType = 'datetime';
    //case DateType     = 'date';
    //case SelectType   = 'select';

    public function getDisplayName(): string
    {
        return match($this) {
            self::TYPE_TEXT => 'Text',
            default => 'Unknown',
        };
    }

    public function getType()
    {
        return $this;
    }
}
