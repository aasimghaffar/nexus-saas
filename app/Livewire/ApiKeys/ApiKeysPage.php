<?php

namespace App\Livewire\ApiKeys;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ApiKeysPage extends Component
{
    public bool $showForm = false;
    public string $tokenName = '';
    public array $abilities = ['read'];

    public ?string $plainTextToken = null;

    public function createToken(): void
    {
        $this->validate([
            'tokenName'   => ['required', 'string', 'max:100'],
            'abilities'   => ['required', 'array', 'min:1'],
            'abilities.*' => ['in:read,write'],
        ], [], ['tokenName' => 'token name']);

        $token = auth()->user()->createToken($this->tokenName, array_values($this->abilities));
        $this->plainTextToken = $token->plainTextToken;

        activity('api')->causedBy(auth()->user())->log("API token created: {$this->tokenName}");
        $this->reset('showForm', 'tokenName');
        $this->abilities = ['read'];
    }

    public function revoke(int $tokenId): void
    {
        auth()->user()->tokens()->where('id', $tokenId)->delete();
        activity('api')->causedBy(auth()->user())->log('API token revoked');
    }

    public function dismissToken(): void
    {
        $this->plainTextToken = null;
    }

    public function render()
    {
        return view('livewire.api-keys.api-keys-page', [
            'tokens' => auth()->user()->tokens()->latest()->get(),
        ])->title('API Keys');
    }
}
