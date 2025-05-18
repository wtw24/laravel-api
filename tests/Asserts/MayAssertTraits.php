<?php

declare(strict_types=1);

namespace Tests\Asserts;

use Exception;
use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use ReflectionClass;
use ReflectionException;

trait MayAssertTraits
{
    protected static function assertUseTraits(Layer $classes, array $traits, string $msg = ''): void
    {
        try {
            $traits = array_filter($traits, static fn ($trait): bool => new ReflectionClass($trait)->isTrait());
        } catch (ReflectionException $e) {
            static::fail('ReflectionException: '.$e->getMessage());
        }

        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $classDescription, string &$msg) use ($traits): bool {
                $traitsInCommon = array_intersect($traits, $classDescription->traits);
                if (count($traitsInCommon) !== count($traits)) {
                    $msg .= "\n".sprintf(
                        '`%%1$s` must use '.(count($traitsInCommon) === 1 ? 'trait %s' : 'traits %s'),
                        implode(', ', array_map(fn ($s): string => "`$s`", $traitsInCommon))
                    );

                    return false;
                }

                return true;
            },
            $msg
        );
    }

    protected static function assertNotUseTraits(Layer $classes, array $traits, string $msg = ''): void
    {
        try {
            $traits = array_filter($traits, static fn ($trait): bool => new ReflectionClass($trait)->isTrait());
        } catch (ReflectionException $e) {
            static::fail('ReflectionException: '.$e->getMessage());
        }

        self::assertForEachClass(
            $classes,
            static function (ObjectDescription $classDescription, string &$msg) use ($traits): bool {
                $traitsInCommon = array_intersect($traits, $classDescription->traits);
                if ($traitsInCommon) {
                    $msg .= "\n".sprintf(
                        '`%%1$s` must not use '.(count($traitsInCommon) === 1 ? 'trait %s' : 'traits %s'),
                        implode(', ', array_map(fn ($s): string => "`$s`", $traitsInCommon))
                    );

                    return false;
                }

                return true;
            },
            $msg
        );
    }

    protected static function assertNotUseBaseClassTraits(Layer $classes, Layer $base, string $msg = ''): void
    {
        $constraint = \PHPUnit\Framework\Assert::callback(static function (Layer $classes) use ($base, $msg): true {
            foreach (static::findMatchingTraitsFromBase($classes, $base) as $offendingClass => $offendingTraits) {
                if ($offendingTraits) {
                    \PHPUnit\Framework\Assert::fail(trim(sprintf(
                        "$msg\n`$offendingClass` must not use traits that are used by the base class `{$base->getName()}` (%s)",
                        implode(', ', array_map(fn ($s): string => "`$s`", $offendingTraits)),
                    )));
                }
            }

            return true;
        });

        \PHPUnit\Framework\Assert::assertThat($classes, $constraint);
    }

    /**
     * @return array<class-string, class-string[]>
     *
     * @throws Exception
     */
    private static function findMatchingTraitsFromBase(Layer $classes, Layer $base): array
    {
        $classes = $classes->excludeByNameStart($base->getName());
        $return = [];
        $baseTraits = iterator_to_array($base->getIterator())[$base->getName()]->traits;
        foreach ($classes as $class) {
            $return[$class->name] = array_intersect($baseTraits, $class->traits);
        }

        return $return;
    }
}
