<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class ParenthesesTest extends TestCase
{
    public function testEmptyStringIsValid()
    {
        $isValid = $this->isValid('');

        $this->assertTrue($isValid);
    }

    public function testBalancedParenthesesAreValid()
    {
        $isValid = $this->isValid('{[()]}');

        $this->assertTrue($isValid);
    }

    public function testUnbalancedParenthesesAreInvalid()
    {
        $isValid = $this->isValid('{[(])}');

        $this->assertFalse($isValid);
    }

    public function testParenthesesInIncorrectOrderAreInvalid()
    {
        $isValid = $this->isValid('({})]');

        $this->assertFalse($isValid);
    }

    private function isValid(string $cadena): bool
    {
        $stack = [];
        $open = ['{', '[', '('];
        $close = ['}', ']', ')'];
        $pairs = ['{}', '[]', '()'];

        for ($i = 0; $i < strlen($cadena); $i++) {
            $char = $cadena[$i];

            if (in_array($char, $open)) {
                array_push($stack, $char);
            } elseif (in_array($char, $close)) {
                if (empty($stack) || !in_array(end($stack) . $char, $pairs)) {
                    return false;
                }
                array_pop($stack);
            }
        }

        return empty($stack);
    }
}
