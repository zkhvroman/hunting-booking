<?php

declare(strict_types=1);

namespace Tests\ItOps;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Src\ItOps\Uuid\Uuid;

#[Group('unit')]
#[CoversClass(Uuid::class)]
final class UuidTest extends TestCase
{
    #[TestWith(['00000000-0000-0000-0000-000000000000'])]
    #[TestWith(['ffffffff-ffff-ffff-ffff-ffffffffffff'])]
    #[TestWith(['f63b06dd-b160-470b-860b-ff436c3ecdec'])]
    public function testIsUuidReturnsTrueForValidUuid(string $validUuid): void
    {
        $isUuid = Uuid::isUuid($validUuid);

        self::assertTrue($isUuid);
    }

    #[TestWith([''])]
    #[TestWith(['abc'])]
    #[TestWith(['f63b06dd-b160-470b-860b-ff436c3ecdex'])]
    public function testIsUuidReturnsFalseForInvalidUuid(string $invalidUuid): void
    {
        $isUuid = Uuid::isUuid($invalidUuid);

        self::assertFalse($isUuid);
    }

    #[TestWith(['00000000-0000-0000-0000-000000000000'])]
    #[TestWith(['ffffffff-ffff-ffff-ffff-ffffffffffff'])]
    #[TestWith(['f63b06dd-b160-470b-860b-ff436c3ecdec'])]
    public function testFromStringCreatesUuidFromValidString(string $validUuid): void
    {
        $uuid = Uuid::fromString($validUuid);

        self::assertSame($uuid->toString(), $validUuid);
    }

    #[TestWith([''])]
    #[TestWith(['abc'])]
    #[TestWith(['f63b06dd-b160-470b-860b-ff436c3ecdex'])]
    public function testFromStringThrowsForInvalidUuid(string $invalidUuid): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Uuid::fromString($invalidUuid);
    }

    public function testV7ReturnsValidV7Uuid(): void
    {
        $v7 = Uuid::v7();

        self::assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[0-9a-f]{4}-[0-9a-f]{12}$/', $v7->toString());
    }

    public function testItCastsToString(): void
    {
        $uuid = Uuid::fromString('b05214cc-8aaa-4335-9520-6ecd1b811dfb');

        $asString = (string) $uuid;

        self::assertSame('b05214cc-8aaa-4335-9520-6ecd1b811dfb', $asString);
    }

    public function testItJsonEncodesAsString(): void
    {
        $uuid = Uuid::fromString('b05214cc-8aaa-4335-9520-6ecd1b811dfb');

        $asString = json_encode($uuid);

        self::assertSame('"b05214cc-8aaa-4335-9520-6ecd1b811dfb"', $asString);
    }

    #[TestWith([null])]
    #[TestWith(['abc'])]
    #[TestWith([1])]
    #[TestWith([new \stdClass()])]
    public function testEqualsReturnsFalseForNonUuidType(mixed $value): void
    {
        $uuid = Uuid::v7();

        $equals = $uuid->equals($value);

        self::assertFalse($equals);
    }

    public function testDeserializedUuidEqualsInitialUuid(): void
    {
        $uuid = Uuid::v7();

        $unserialized = unserialize(serialize($uuid));
        $equals = $uuid->equals($unserialized);

        self::assertTrue($equals);
    }

    public function testItGrowsMonotonously(): void
    {
        $time = new \DateTimeImmutable();

        $uuid = Uuid::v7($time)->toString();
        $uuid2 = Uuid::v7($time->modify('+1 ms'))->toString();

        self::assertGreaterThan($uuid, $uuid2);
    }

    public function testItGeneratesValidUuidV7FromTime(): void
    {
        $time = new \DateTimeImmutable('2022-11-03T16:42:58.485', new \DateTimeZone('Europe/Moscow'));
        $uuid = Uuid::v7($time);

        $ramseyUuid = RamseyUuid::getFactory()->fromString($uuid->toString());

        self::assertInstanceOf(UuidV7::class, $ramseyUuid);
        self::assertEquals($time, $ramseyUuid->getDateTime());
    }
}
