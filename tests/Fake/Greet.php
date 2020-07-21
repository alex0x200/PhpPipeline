<?php declare(strict_types = 1);

namespace Test\Fake;

class Greet
{
    private string $message;

    /**
     * Dummy constructor.
     * @param string $greed
     */
    public function __construct(string $greed)
    {
        $this->message = $greed;
    }

    /**
     * @return string
     */
    public function printGreet(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function printGreetWithExpression(): string
    {
        return sprintf("%s!1!1!1!",$this->message);
    }
}