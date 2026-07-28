<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Models\User;

class UpdateUserRequest extends StoreUserRequest
{
    /**
     * @param  array<array-key, string|string[]> |null  $rules
     * @return array<array-key, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        $user = $this->parameter('user', User::class);

        $rules = parent::rules(User::getRulesForUpdate($user));

        // PATCH updates may change only one field, such as the SFTP password.
        foreach (['email', 'username'] as $field) {
            $rules[$field] = array_values(array_filter(
                $rules[$field],
                static fn (mixed $rule): bool => $rule !== 'required',
            ));
            $rules[$field][] = 'sometimes';
        }

        return $rules;
    }
}
