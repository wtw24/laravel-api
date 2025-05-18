<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use Tests\Asserts\MayAssertClass;
use Tests\Asserts\MayAssertEnums;
use Tests\Asserts\MayAssertTraits;

trait ArchitectureAsserts
{
    use MayAssertClass,
        MayAssertEnums,
        MayAssertTraits;

    /**
     * @param  callable(ObjectDescription, string=):bool  $classCheckCallback
     */
    final protected static function assertForEachClass(
        Layer $classes,
        callable $classCheckCallback,
        string $baseMsg = ''
    ): void {
        $callback = static function (Layer $classes) use ($classCheckCallback, $baseMsg): true {
            $failures = [];

            foreach ($classes as $class) {
                $msg = $baseMsg;
                if (! $classCheckCallback($class, $msg)) {
                    $failures[] = [$class, $msg];
                }
            }

            if ($failures) {
                \PHPUnit\Framework\Assert::fail(trim(implode(
                    "\n",
                    array_map(
                        static fn (array $tuple): string => sprintf(
                            $tuple[1] ?: 'Assertion for class `%1$s` failed.',
                            $tuple[0]->name
                        ),
                        $failures
                    )
                )));
            }

            return true;
        };

        \PHPUnit\Framework\Assert::assertThat(
            $classes,
            \PHPUnit\Framework\Assert::callback($callback),
        );
    }
}
