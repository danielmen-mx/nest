<?php

namespace App\Http\Requests\Cupboard\User;

use App\Models\Cupboard\User;
use Exception;

trait ValidateFieldTrait
{
    private $user;

    public function setUser($userId)
    {
        $this->user = User::query()->where('uuid', $userId)->firstOrFail();
    }

    public function validateField($field, $request)
    {
        if (!$request) return;
        if (str_contains($request, ' ')) return $this->throwValidationException($field."_empty_strings");
        $query = User::all("uuid", "username", "email");
        $query->map(function ($item) use ($request, $field) {
            if ($item->uuid === $this->user->uuid && $item->$field === $request) return $this->throwValidationException("duplicated_".$field);
            if ($item->$field === $request) return $this->throwValidationException($field."_in_use");
        });
    }

    public function validatePassword($password)
    {
        if (!$password) return;
        if (!password_verify($password, $this->user->password)) return;
        $this->throwValidationException("duplicated_password");
    }

    public function validateLanguage($language)
    {
        $languagesAvailables = ['es', 'en'];
        if (!array_reduce($languagesAvailables, fn($a, $n) => $a || str_contains($language, $n), false)) $this->throwValidationException("language");
    }

    private function throwValidationException($key)
    {
        throw new Exception($this->translation($key));
    }

    private function translation($key)
    {
        return __('api_error.users.validation.' . $key);
    }
}