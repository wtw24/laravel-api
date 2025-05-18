<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase;
use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
abstract class ArchitectureTestCase extends TestCase
{
    use ArchitectureAsserts,
        ArchitectureOverrides;

    #[\Override]
    public static function setUpBeforeClass(): void
    {
        static::initLayer();
    }

    /**
     * @param  class-string  $className
     */
    protected static function singleClass(string $className): Layer
    {
        return static::initLayer()->leaveByNameStart($className);
    }

    /**
     * @param  class-string  $namespace
     */
    protected static function classesByNamespace(string $namespace, bool $regex = false): Layer
    {
        $layer = static::initLayer()->excludeByNameStart('Tests');
        if ($regex) {
            return $layer->leaveByNameRegex($namespace);
        }

        return $layer->leaveByNameStart($namespace);
    }

    /**
     * @param  class-string  $namespace
     */
    protected static function excludedClassesByNamespace(string $namespace, bool $regex = false): Layer
    {
        $layer = static::initLayer()->excludeByNameStart('Tests');
        if ($regex) {
            return $layer->excludeByNameRegex($namespace);
        }

        return $layer->excludeByNameStart($namespace);
    }
}
