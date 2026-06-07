<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../private/helpers.php';

class HelpersTest extends TestCase
{
    // Nulstiller SESSION og POST før hver test så tidligere tests ikke påvirker hinanden
    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST = [];
    }

    /* ==================================================
    ADGANGSKODE
    ================================================== */

    // Tester at validate_password() returnerer adgangskoden uændret når den opfylder
    // minimumskravet på 6 tegn
    public function testValidatePassword()
    {
        $_POST['user_password'] = '123456';

        $this->assertEquals(
            '123456',
            validate_password()
        );
    }

    // Tester at validate_password() kaster en Exception når adgangskoden er under
    // 6 tegn, så for korte koder aldrig accepteres
    public function testValidatePasswordFails()
    {
        $this->expectException(Exception::class);

        $_POST['user_password'] = '123';

        validate_password();
    }

    /* ==================================================
    EMAIL
    ================================================== */

    // Tester at validate_email() konverterer store bogstaver til små og returnerer
    // den gyldige email — sikrer konsistent format i databasen
    public function testValidateEmail()
    {
        $_POST['user_email'] = 'TEST@TEST.DK';

        $this->assertEquals(
            'test@test.dk',
            validate_email()
        );
    }

    // Tester at validate_email() kaster en Exception når emailen ikke har
    // et gyldigt format (mangler @, domæne osv.)
    public function testValidateEmailFails()
    {
        $this->expectException(Exception::class);

        $_POST['user_email'] = 'forkert-email';

        validate_email();
    }

    /* ==================================================
    XSS / OUTPUT
    ================================================== */

    // Tester at e() konverterer HTML-tegn som < og > til deres HTML-entiteter
    // så farlig kode ikke kan køres i browseren (XSS-beskyttelse)
    public function testEscapeHtml()
    {
        $this->assertEquals(
            '&lt;script&gt;',
            e('<script>')
        );
    }

    // Tester at e() håndterer null uden at fejle og returnerer en tom streng
    // i stedet undgår PHP-warnings ved manglende data
    public function testEscapeHtmlHandlesNull(): void
    {
        $this->assertEquals('', e(null));
    }

    /* ==================================================
    FLASH-BESKEDER
    ================================================== */

    // Tester at flash() returnerer den gemte besked første gang den kaldes,
    // og derefter returnerer null beskeden må kun vises én gang
    public function testFlashReturnsAndClearsMessage(): void
    {
        $_SESSION['success'] = 'Det virkede!';
        $this->assertEquals('Det virkede!', flash('success'));
        $this->assertNull(flash('success'));
    }

    // Tester at flash() returnerer null når der ikke er gemt nogen besked
    // under den angivne nøgle
    public function testFlashReturnsNullIfNotSet(): void
    {
        $this->assertNull(flash('findes_ikke'));
    }

    /* ==================================================
    OLD INPUT
    ================================================== */

    // Tester at old() returnerer en tom streng som standard når feltet
    // ikke findes i POST bruges til at bevare formulardata ved fejl
    public function testOldReturnsEmptyStringByDefault(): void
    {
        $this->assertEquals('', old('navn'));
    }

    // Tester at old() returnerer den angivne standardværdi i stedet for
    // tom streng når feltet ikke er sat
    public function testOldReturnsCustomDefault(): void
    {
        $this->assertEquals('Standard', old('navn', 'Standard'));
    }

    /* ==================================================
    CSRF
    ================================================== */

    // Tester at csrf_verify() returnerer false når der slet ikke er noget
    // token i hverken session eller POST — forhindrer tomme anmodninger
    public function testCsrfVerifyFailsWithoutTokens(): void
    {
        $this->assertFalse(csrf_verify());
    }

    // Tester at csrf_verify() returnerer true når session-token og POST-token
    // er identiske anmodningen er legitim
    public function testCsrfVerifyPassesWithMatchingTokens(): void
    {
        $_SESSION['csrf_token'] = 'abc123';
        $_POST['csrf_token'] = 'abc123';
        $this->assertTrue(csrf_verify());
    }

    // Tester at csrf_verify() returnerer false når POST-token ikke stemmer
    // overens med session-token beskytter mod cross-site request forgery
    public function testCsrfVerifyFailsWithMismatchedTokens(): void
    {
        $_SESSION['csrf_token'] = 'abc123';
        $_POST['csrf_token'] = 'forkert';
        $this->assertFalse(csrf_verify());
    }

    /* ==================================================
    LOGIN / ROLLER
    ================================================== */

    // Tester at is_logged_in() returnerer false når der ikke er nogen bruger
    // i sessionen besøgende er ikke logget ind
    public function testIsLoggedInReturnsFalseByDefault(): void
    {
        $this->assertFalse(is_logged_in());
    }

    // Tester at is_logged_in() returnerer true når en bruger er gemt i sessionen
    public function testIsLoggedInReturnsTrueWhenUserSet(): void
    {
        $_SESSION['user'] = ['user_pk' => 1, 'role_fk' => 3];
        $this->assertTrue(is_logged_in());
    }

    // Tester at is_admin() returnerer true når brugerens rolle er 1 (admin)
    public function testIsAdminReturnsTrueForRole1(): void
    {
        $_SESSION['user'] = ['role_fk' => 1];
        $this->assertTrue(is_admin());
    }

    // Tester at is_admin() returnerer false når brugerens rolle er 3 (almindelig bruger)
    // så adminfunktioner ikke er tilgængelige for andre
    public function testIsAdminReturnsFalseForRole3(): void
    {
        $_SESSION['user'] = ['role_fk' => 3];
        $this->assertFalse(is_admin());
    }

    // Tester at is_member() returnerer true når brugerens rolle er 2 (vejleder/medlem)
    public function testIsMemberReturnsTrueForRole2(): void
    {
        $_SESSION['user'] = ['role_fk' => 2];
        $this->assertTrue(is_member());
    }

    // Tester at is_user() returnerer true når brugerens rolle er 3 (almindelig bruger)
    public function testIsUserReturnsTrueForRole3(): void
    {
        $_SESSION['user'] = ['role_fk' => 3];
        $this->assertTrue(is_user());
    }

    /* ==================================================
    DATO-FORMATERING
    ================================================== */

    // Tester at formatDanishDate() formaterer en dato korrekt til dansk kort format
    // med månedsnavn på dansk her tester vi januar
    public function testFormatDanishDateJanuary(): void
    {
        $this->assertEquals('01 jan', formatDanishDate('2024-01-01'));
    }

    // Tester at formatDanishDate() formaterer korrekt for en dato midt på året
    // her 15. maj
    public function testFormatDanishDateMay(): void
    {
        $this->assertEquals('15 maj', formatDanishDate('2024-05-15'));
    }

    /* ==================================================
    TEKSTVALIDERING
    ================================================== */

    // Tester at validate_text() fjerner whitespace fra begge ender og returnerer
    // den rensede tekst når den er gyldig
    public function testValidateTextReturnsTrimedValue(): void
    {
        $_POST['navn'] = '  Hans  ';
        $this->assertEquals('Hans', validate_text('navn', 'Navn'));
    }

    // Tester at validate_text() kaster en Exception når feltet er tomt
    // så tomme formularfelter ikke accepteres
    public function testValidateTextThrowsIfEmpty(): void
    {
        $this->expectException(Exception::class);
        $_POST['navn'] = '';
        validate_text('navn', 'Navn');
    }

    // Tester at validate_text() kaster en Exception når teksten er kortere end
    // den angivne minimumslængde her kræves mindst 5 tegn men kun 2 sendes
    public function testValidateTextThrowsIfTooShort(): void
    {
        $this->expectException(Exception::class);
        $_POST['kode'] = 'AB';
        validate_text('kode', 'Kode', 5);
    }

    /* ==================================================
    POST ID VALIDERING
    ================================================== */

    // Tester at validate_post_id() kaster en Exception når id er 0
    // da et gyldigt database-id altid skal være et positivt heltal
    public function testValidatePostIdThrowsIfZero(): void
    {
        $this->expectException(Exception::class);
        $_POST['event_id'] = '0';
        validate_post_id('event_id');
    }

    // Tester at validate_post_id() kaster en Exception når id er negativt
    // da negative tal aldrig kan være gyldige database-id'er
    public function testValidatePostIdThrowsIfNegative(): void
    {
        $this->expectException(Exception::class);
        $_POST['event_id'] = '-5';
        validate_post_id('event_id');
    }
}
