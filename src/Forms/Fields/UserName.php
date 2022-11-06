<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserName extends Field
{
    public function __construct(public ?User $model)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field
                name="name"
                :label="__('waterhole::admin.user-name-label')"
            >
                <input
                    type="text"
                    name="name"
                    id="{{ $component->id }}"
                    class="input"
                    value="{{ old('name', $model->name ?? null) }}"
                    autofocus
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->user)],
        ]);
    }

    public function saving(FormRequest $request): void
    {
        $this->model->name = $request->validated('name');
    }
}