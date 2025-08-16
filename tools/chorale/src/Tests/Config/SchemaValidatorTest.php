<?php

declare(strict_types=1);

namespace Chorale\Tests\Config;

use Chorale\Config\SchemaValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchemaValidator::class)]
#[Group('unit')]
#[Small]
final class SchemaValidatorTest extends TestCase
{
    public function testValidateRejectsNonStringRepoHost(): void
    {
        $v = new SchemaValidator();
        $issues = $v->validate(['repo_host' => 123], '/unused');
        $this->assertContains("Key 'repo_host' must be a string.", $issues);
    }

    #[Test]
    public function testValidateRejectsRulesNotArray(): void
    {
        $v = new SchemaValidator();
        $issues = $v->validate(['rules' => 'x'], '/unused');
        $this->assertContains("Key 'rules' must be an array.", $issues);
    }

    #[Test]
    public function testValidateRejectsKeepHistoryNotBool(): void
    {
        $v = new SchemaValidator();
        $issues = $v->validate(['rules' => ['keep_history' => 'no']], '/unused');
        $this->assertContains('rules.keep_history must be a boolean.', $issues);
    }

    #[Test]
    public function testValidateRejectsPatternsNotArray(): void
    {
        $v = new SchemaValidator();
        $issues = $v->validate(['patterns' => 'x'], '/unused');
        $this->assertContains("Key 'patterns' must be a list.", $issues);
    }

    #[Test]
    public function testValidateRejectsPatternMissingMatch(): void
    {
        $v = new SchemaValidator();
        $issues = $v->validate(['patterns' => [[]]], '/unused');
        $this->assertContains('patterns[0].match must be a string.', $issues);
    }

    #[Test]
    public function testValidateRejectsTargetsFieldTypes(): void
    {
        $v = new SchemaValidator();
        $issues = $v->validate(['targets' => [['name' => 1]]], '/unused');
        $this->assertContains('targets[0].name must be a string.', $issues);
    }

    #[Test]
    public function testValidateRejectsHooksWhenNotList(): void
    {
        $v = new SchemaValidator();
        $issues = $v->validate(['hooks' => 'not-a-list'], '/unused');
        $this->assertContains("Key 'hooks' must be a list.", $issues);
    }
}
