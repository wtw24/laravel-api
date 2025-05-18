<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/public',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withTypeCoverageLevel(47)
    ->withDeadCodeLevel(44)
    ->withSets([
        LaravelSetList::LARAVEL_120,
    ])
    ->withRules([
        DeclareStrictTypesRector::class,
    ])
    ->withSkip([
        StaticCallOnNonStaticToInstanceCallRector::class,
        FirstClassCallableRector::class,
        __DIR__.'/bootstrap/cache',
    ])
    ->withPhpSets(
        php84: true
    );
