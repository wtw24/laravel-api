<?php

declare(strict_types=1);

namespace Tests\Asserts;

use Attribute;
use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Enums\Visibility;

trait MayAssertClass
{
    protected static function assertNameSuffix(Layer $classes, string $suffix, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static fn (ObjectDescription $class): bool => str_ends_with($class->name, $suffix),
            "$msg\nClass name of `%1\$s` must start with `$suffix`."
        );
    }

    protected static function assertNamePrefix(Layer $classes, string $prefix, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static fn (ObjectDescription $class): bool => str_starts_with($class->name, $prefix),
            "$msg\nClass name of `%1\$s` must start with `$prefix`."
        );
    }

    protected static function assertIsAbstract(Layer $classes, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static fn (ObjectDescription $class): bool => $class->reflectionClass->isAbstract(),
            "$msg\n`%1\$s` needs to be abstract."
        );
    }

    protected static function assertIsNotAbstract(Layer $classes, string $msg = ''): void
    {
        self::assertForEachClass(
            $classes,
            static fn (ObjectDescription $class): bool => ! $class->reflectionClass->isAbstract(),
            "$msg\n`%1\$s` must not be abstract."
        );
    }

    /**
     * @param  Layer|class-string  $extendingClass
     */
    protected static function assertShouldExtend(Layer $classes, string|Layer $extendingClass, string $msg = ''): void
    {
        if (! is_string($extendingClass)) {
            $extendingClass = $extendingClass->getName();
        }

        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $class, string &$msg) use ($extendingClass): bool {
                if (
                    $class->type->value === 'class'
                    && $class->name !== $extendingClass
                    && ! $class->reflectionClass->isSubclassOf($extendingClass)
                ) {
                    $msg .= "\n`%1\$s` must extend `$extendingClass`.";

                    return false;
                }

                return true;
            },
            $msg,
        );
    }

    /**
     * @param  Layer|class-string  $extendingClass
     */
    protected static function assertShouldNotExtend(Layer $classes, string|Layer $extendingClass, string $msg = ''): void
    {
        if (! is_string($extendingClass)) {
            $extendingClass = $extendingClass->getName();
        }

        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $class, string &$msg) use ($extendingClass): bool {
                if (
                    $class->type->value === 'class'
                    && $class->name !== $extendingClass
                    && $class->reflectionClass->isSubclassOf($extendingClass)
                ) {
                    $msg .= "\n`%1\$s` must not extend `$extendingClass`.";

                    return false;
                }

                return true;
            },
            $msg,
        );
    }

    protected static function assertPropertyHasVisibility(
        Layer $classes,
        string $propertyName,
        Visibility $visibility,
        string $msg = '',
    ): void {
        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $class, string &$msg) use ($propertyName, $visibility): bool {
                $properties = array_column(iterator_to_array($class->properties), null, 'name');
                if (
                    array_key_exists($propertyName, $properties)
                    && $visibility !== $properties[$propertyName]->visibility
                ) {
                    $msg .= "\nProperty `%1\$s::$propertyName` needs to be `$visibility->value`.";

                    return false;
                }

                return true;
            },
            $msg
        );
    }

    /**
     * @param  class-string<Attribute>  $attribute
     */
    protected static function assertClassAttachingAttribute(
        Layer $classes,
        string $attribute,
        string $msg = '',
    ): void {
        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $class, string &$msg) use ($attribute): bool {
                if (! $class->reflectionClass->getAttributes($attribute)) {
                    $msg .= "\n`%1\$s` needs to attach `$attribute`.";

                    return false;
                }

                return true;
            },
            $msg
        );
    }
}
