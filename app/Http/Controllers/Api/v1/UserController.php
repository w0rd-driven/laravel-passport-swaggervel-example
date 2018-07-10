<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\UserRequest;
use App\Models\Api\v1\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @SWG\Get(
     *   tags={"users"},
     *   path="/users",
     *   summary="Retrieve a list of user records.",
     *   description="",
     *   operationId="getUserList",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="List of users found.",
     *     @SWG\Schema(
     *       type="object",
     *       @SWG\Property(
     *         property="status",
     *         type="string"
     *       ),
     *       @SWG\Property(
     *         property="data",
     *         @SWG\Property(
     *           property="users",
     *           type="array",
     *           @SWG\Items(ref="#/definitions/User"),
     *         )
     *       )
     *     )
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Invalid credentials provided.",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="List of users not found."
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="An ""unexpected"" error occurred."
     *   ),
     *   security={
     *     {
     *        "passport-swaggervel_auth": {"*"}
     *     }
     *   },
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = min($request->get('per_page'), 1000);
        $items = User::latest()->paginate($pageSize);
        // TODO: Setup some sort of transformer to turn paginate() into this object
        $response = [
            'pagination' => [
                'current_page' => $items->currentPage(),
                'from' => $items->firstItem(),
                'last_page' => $items->lastPage(),
                'next_page_url' => $items->nextPageUrl(),
                'path' => $request->url(),
                'per_page' => $items->perPage(),
                'prev_page_url' => $items->previousPageUrl(),
                'to' => $items->lastItem(),
                'total' => $items->total(),
            ],
            'users' => $items->items(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $response,
        ]);
    }

    /**
     * @SWG\Post(
     *   tags={"users"},
     *   path="/users",
     *   summary="Add a new user record.",
     *   description="",
     *   operationId="postUser",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="User object.",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/UserRequest"),
     *   ),
     *   @SWG\Response(
     *     response=201,
     *     description="User created successfully.",
     *     @SWG\Schema(
     *       type="object",
     *       @SWG\Property(
     *         property="status",
     *         type="string"
     *       ),
     *       @SWG\Property(
     *         property="data",
     *         @SWG\Property(
     *           property="user",
     *           ref="#/definitions/User"
     *         )
     *       )
     *     )
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Invalid credentials provided.",
     *   ),
     *   @SWG\Response(
     *     response=422,
     *     description="Validation failed.",
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="An ""unexpected"" error occurred."
     *   ),
     *   security={
     *     {
     *        "passport-swaggervel_auth": {"*"}
     *     }
     *   },
     * )
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\v1\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $fields = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ];
        if (config('auth.providers.users.field', 'email') === 'username' && isset($data['username'])) {
            $fields['username'] = $data['username'];
        }
        $model = \DB::transaction(function () use ($fields) {
            $user = User::create($fields);
            return $user;
        });
        $response = [
            'user' => $model,
        ];
        return response()->json([
            'status' => 'success',
            'data' => $response,
        ], 201);
    }

    /**
     * @SWG\Get(
     *   tags={"users"},
     *   path="/users/{id}",
     *   summary="Retrieve a single user record by id.",
     *   description="",
     *   operationId="getUser",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="The id. Use 1 for testing.",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="User found.",
     *     @SWG\Schema(
     *       type="object",
     *       @SWG\Property(
     *         property="status",
     *         type="string"
     *       ),
     *       @SWG\Property(
     *         property="data",
     *         @SWG\Property(
     *           property="user",
     *           ref="#/definitions/User"
     *         )
     *       )
     *     )
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Invalid credentials provided.",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found."
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="An ""unexpected"" error occurred."
     *   ),
     *   security={
     *     {
     *        "passport-swaggervel_auth": {"*"}
     *     }
     *   },
     * )
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\v1\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $response = [
            'user' => $user,
        ];
        return response()->json([
            'status' => 'success',
            'data' => $response,
        ]);
    }

    /**
     * @SWG\Put(
     *   tags={"users"},
     *   path="/users/{id}",
     *   summary="Update a single user record by id.",
     *   description="",
     *   operationId="putUser",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="The id. Use 1 for testing.",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="User object.",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/UserRequest"),
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="User updated successfully.",
     *     @SWG\Schema(
     *       type="object",
     *       @SWG\Property(
     *         property="status",
     *         type="string"
     *       ),
     *       @SWG\Property(
     *         property="data",
     *         @SWG\Property(
     *           property="user",
     *           ref="#/definitions/User"
     *         )
     *       )
     *     )
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Invalid credentials provided.",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found."
     *   ),
     *   @SWG\Response(
     *     response=422,
     *     description="Validation failed.",
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="An ""unexpected"" error occurred."
     *   ),
     *   security={
     *     {
     *        "passport-swaggervel_auth": {"*"}
     *     }
     *   },
     * )
     */
    /**
     * @SWG\Patch(
     *   tags={"users"},
     *   path="/users/{id}",
     *   summary="Partially update a single user record by id.",
     *   description="",
     *   operationId="patchUser",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="The id. Use 1 for testing.",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Partial User object.",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/UserRequest"),
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="User updated successfully.",
     *     @SWG\Schema(
     *       type="object",
     *       @SWG\Property(
     *         property="status",
     *         type="string"
     *       ),
     *       @SWG\Property(
     *         property="data",
     *         @SWG\Property(
     *           property="user",
     *           ref="#/definitions/User"
     *         )
     *       )
     *     )
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Invalid credentials provided.",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found."
     *   ),
     *   @SWG\Response(
     *     response=422,
     *     description="Validation failed.",
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="An ""unexpected"" error occurred."
     *   ),
     *   security={
     *     {
     *        "passport-swaggervel_auth": {"*"}
     *     }
     *   },
     * )
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\v1\UserRequest $request
     * @param  \App\Models\Api\v1\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $fields = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ];
        if (config('auth.providers.users.field', 'email') === 'username' && isset($data['username'])) {
            $fields['username'] = $data['username'];
        }

        $model = \DB::transaction(function () use ($user, $fields) {
            if ($user->deleted_at) {
                $user->restore();
            }
            $isUpdated = $user->update($fields);
            return $user;
        });
        $response = [
            'user' => $model,
        ];
        return response()->json([
            'status' => 'success',
            'data' => $response,
        ]);
    }

    /**
     * @SWG\Delete(
     *   tags={"users"},
     *   path="/users/{id}",
     *   summary="Remove a single user record by id.",
     *   description="",
     *   operationId="deleteUser",
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="The id. Use 1 for testing.",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *     response=204,
     *     description="User deleted successfully.",
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Invalid credentials provided.",
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   ),
     *   security={
     *     {
     *        "passport-swaggervel_auth": {"*"}
     *     }
     *   },
     * )
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\v1\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([], 204);
    }
}
