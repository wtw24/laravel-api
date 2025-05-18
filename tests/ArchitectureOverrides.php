<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Architecture\ArchitectureAsserts as BaseArchitectureAsserts;
use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Services\ServiceContainer;
use PHPUnit\Architecture\Storage\ObjectsStorage;

trait ArchitectureOverrides
{
    use BaseArchitectureAsserts;

    private static ?Layer $layer = null;

    protected static array $excludedPaths = [];

    #[\Deprecated]
    public function layer(): Layer
    {
        return static::initLayer();
    }

    public static function initLayer(): Layer
    {
        if (self::$layer === null) {
            ServiceContainer::init(self::excludedPaths());

            self::$layer = new Layer(ObjectsStorage::getObjectMap());
        }

        return self::$layer;
    }

    protected static function excludedPaths(): array
    {
        return ['vendor', ...static::$excludedPaths];
    }
}
