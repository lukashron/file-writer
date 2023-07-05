<?php declare(strict_types=1);

use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/src/']);
    $rectorConfig->phpVersion(PhpVersion::PHP_72);
    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::CODING_STYLE,
        SetList::CODE_QUALITY
    ]);
};