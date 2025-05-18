<?php

declare(strict_types=1);

namespace Tests\Asserts;

use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use ReflectionEnum;

trait MayAssertEnums
{
    protected static function assertIsEnum(Layer $classes, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static fn (ObjectDescription $class): bool => $class->reflectionClass->isEnum(),
            "$msg\n`%1\$s` is not Enum."
        );
    }

    protected static function assertIsNotEnum(Layer $classes, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static fn (ObjectDescription $class): bool => ! $class->reflectionClass->isEnum(),
            "$msg\n`%1\$s` is Enum."
        );
    }

    protected static function assertEnumIsBacked(Layer $classes, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static fn (ObjectDescription $class): bool => $class->reflectionClass->isEnum()
                && new ReflectionEnum($class->name)->isBacked(),
            "$msg\nEnum `%1\$s` must be backed."
        );
    }

    protected static function assertEnumIsBackedByString(Layer $classes, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $class): bool {
                if (! $class->reflectionClass->isEnum()) {
                    return false;
                }
                $reflectionEnum = new ReflectionEnum($class->name);

                return $reflectionEnum->isBacked()
                    && $reflectionEnum->getBackingType()?->getName() === 'string';
            },
            "$msg\nEnum `%1\$s` must be backed by string."
        );
    }

    protected static function assertEnumIsBackedByInteger(Layer $classes, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $class): bool {
                if (! $class->reflectionClass->isEnum()) {
                    return false;
                }
                $reflectionEnum = new ReflectionEnum($class->name);

                return $reflectionEnum->isBacked()
                    && $reflectionEnum->getBackingType()?->getName() === 'int';
            },
            "$msg\nEnum `%1\$s` must be backed by integer."
        );
    }
}
