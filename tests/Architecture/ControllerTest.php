<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Http\Controllers\Controller;
use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Framework\Attributes\Test;
use Tests\ArchitectureTestCase;

final class ControllerTest extends ArchitectureTestCase
{
    private const string BaseController = Controller::class;

    private function baseController(): Layer
    {
        return self::singleClass(self::BaseController);
    }

    #[Test]
    public function baseControllerIsAbstract(): void
    {
        self::assertIsAbstract(self::baseController());
    }
}
