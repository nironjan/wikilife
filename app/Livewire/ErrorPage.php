<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.front')]
class ErrorPage extends Component
{
    public $errorCode;
    public $errorMessage;
    public $robotMessage;

    public function mount($code = 404, $message = null)
    {
        $this->errorCode = $code;
        $this->errorMessage = $message ?? $this->getDefaultMessage($code);
        $this->robotMessage = $this->getRobotMessage($code);
    }

    private function getDefaultMessage($code)
    {
        return match($code) {
            500 => 'Internal Server Error',
            403 => 'Access Denied',
            419 => 'Page Expired',
            429 => 'Too Many Requests',
            default => 'An Error Occurred',
        };
    }

    private function getRobotMessage($code)
    {
        return match($code) {
            500 => [
                'My internal systems encountered a glitch.',
                'Our team of robotic engineers has been alerted.',
                'Please try again in a few moments.'
            ],
            default => [
                'Something unexpected happened in my circuits.',
                'Don\'t worry, I\'m working on a solution.',
                'Try refreshing the page or come back later.'
            ]
        };
    }

    public function render()
    {
        return view('livewire.error-page');
    }
}
