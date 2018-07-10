<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected static $TABLENAME = 'users';
    protected static $PARAM_ID = 'user';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];

        $method = $this->method();
        $route = $this->route(static::$PARAM_ID);
        if ($route) {
            $user_id = $route->id;
            switch ($method) {
                case 'PUT':
                    $rules['email'] = "{$rules['email']},$user_id";
                    break;
                case 'PATCH': {
                    $rules['name'] = 'sometimes|max:255';
                    $rules['email'] = "{$rules['email']},$user_id";
                    $rules['password'] = 'sometimes|min:6|confirmed';
                    break;
                }
                default: {
                    break;
                }
            }
        }

        return $rules;
    }
}
