<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

/**
 * @see https://www.php.net/manual/en/features.file-upload.errors.php
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
enum UploadedFileError: int
{
    case OK            = \UPLOAD_ERR_OK;
    case INI_SIZE      = \UPLOAD_ERR_INI_SIZE;
    case FORM_SIZE     = \UPLOAD_ERR_FORM_SIZE;
    case PARTIAL       = \UPLOAD_ERR_PARTIAL;
    case NO_FILE       = \UPLOAD_ERR_NO_FILE;
    case NO_TMP_DIR    = \UPLOAD_ERR_NO_TMP_DIR;
    case CANT_WRITE    = \UPLOAD_ERR_CANT_WRITE;
    case ERR_EXTENSION = \UPLOAD_ERR_EXTENSION;
}
