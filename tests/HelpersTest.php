<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../private/helpers.php';

class HelpersTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST = [];
    }

    public function testValidatePassword()
    {
        $_POST['user_password'] = '123456';

        $this->assertEquals(
            '123456',
            validate_password()
        );
    }

    public function testValidatePasswordFails()
    {
        $this->expectException(Exception::class);

        $_POST['user_password'] = '123';

        validate_password();
    }

    public function testEscapeHtml()
{
    $this->assertEquals(
        '&lt;script&gt;',
        e('<script>')
    );
}

public function testValidateEmail()
{
    $_POST['user_email'] = 'TEST@TEST.DK';

    $this->assertEquals(
        'test@test.dk',
        validate_email()
    );
}

public function testValidateEmailFails()
{
    $this->expectException(Exception::class);

    $_POST['user_email'] = 'forkert-email';

    validate_email();
}

    public function testEscapeHtmlHandlesNull(): void
    {
        $this->assertEquals('', e(null));
    }

    public function testFlashReturnsAndClearsMessage(): void
    {
        $_SESSION['success'] = 'Det virkede!';
        $this->assertEquals('Det virkede!', flash('success'));
        $this->assertNull(flash('success'));
    }

    public function testFlashReturnsNullIfNotSet(): void
    {
        $this->assertNull(flash('findes_ikke'));
    }

    public function testOldReturnsEmptyStringByDefault(): void
    {
        $this->assertEquals('', old('navn'));
    }

    public function testOldReturnsCustomDefault(): void
    {
        $this->assertEquals('Standard', old('navn', 'Standard'));
    }

    public function testCsrfVerifyFailsWithoutTokens(): void
    {
        $this->assertFalse(csrf_verify());
    }

    public function testCsrfVerifyPassesWithMatchingTokens(): void
    {
        $_SESSION['csrf_token'] = 'abc123';
        $_POST['csrf_token'] = 'abc123';
        $this->assertTrue(csrf_verify());
    }

    public function testCsrfVerifyFailsWithMismatchedTokens(): void
    {
        $_SESSION['csrf_token'] = 'abc123';
        $_POST['csrf_token'] = 'forkert';
        $this->assertFalse(csrf_verify());
    }

    public function testIsLoggedInReturnsFalseByDefault(): void
    {
        $this->assertFalse(is_logged_in());
    }

    public function testIsLoggedInReturnsTrueWhenUserSet(): void
    {
        $_SESSION['user'] = ['user_pk' => 1, 'role_fk' => 3];
        $this->assertTrue(is_logged_in());
    }

    public function testIsAdminReturnsTrueForRole1(): void
    {
        $_SESSION['user'] = ['role_fk' => 1];
        $this->assertTrue(is_admin());
    }

    public function testIsAdminReturnsFalseForRole3(): void
    {
        $_SESSION['user'] = ['role_fk' => 3];
        $this->assertFalse(is_admin());
    }

    public function testIsMemberReturnsTrueForRole2(): void
    {
        $_SESSION['user'] = ['role_fk' => 2];
        $this->assertTrue(is_member());
    }

    public function testIsUserReturnsTrueForRole3(): void
    {
        $_SESSION['user'] = ['role_fk' => 3];
        $this->assertTrue(is_user());
    }

    public function testFormatDanishDateJanuary(): void
    {
        $this->assertEquals('01 jan', formatDanishDate('2024-01-01'));
    }

    public function testFormatDanishDateMay(): void
    {
        $this->assertEquals('15 maj', formatDanishDate('2024-05-15'));
    }

    public function testValidateTextReturnsTrimedValue(): void
    {
        $_POST['navn'] = '  Hans  ';
        $this->assertEquals('Hans', validate_text('navn', 'Navn'));
    }

    public function testValidateTextThrowsIfEmpty(): void
    {
        $this->expectException(Exception::class);
        $_POST['navn'] = '';
        validate_text('navn', 'Navn');
    }

    public function testValidateTextThrowsIfTooShort(): void
    {
        $this->expectException(Exception::class);
        $_POST['kode'] = 'AB';
        validate_text('kode', 'Kode', 5);
    }



    public function testValidatePostIdThrowsIfZero(): void
    {
        $this->expectException(Exception::class);
        $_POST['event_id'] = '0';
        validate_post_id('event_id');
    }

    public function testValidatePostIdThrowsIfNegative(): void
    {
        $this->expectException(Exception::class);
        $_POST['event_id'] = '-5';
        validate_post_id('event_id');
    }
}