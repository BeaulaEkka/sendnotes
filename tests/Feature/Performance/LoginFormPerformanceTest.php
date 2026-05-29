<?php

namespace Tests\Feature\Performance;

use App\Livewire\Forms\LoginForm;
use Tests\TestCase;

class LoginFormPerformanceTest extends TestCase
{
    /** @test */
    public function throttle_key_is_memoized()
    {
        $component = new class extends \Livewire\Component
        {
        };
        $form = new LoginForm($component, 'login');
        $form->email = 'TEST@EXAMPLE.COM';

        $key1 = $this->invokeMethod($form, 'throttleKey');
        $key2 = $this->invokeMethod($form, 'throttleKey');

        $this->assertEquals($key1, $key2);
        $this->assertStringContainsString('test@example.com|', $key1);
    }

    /**
     * Helper to call protected methods.
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
