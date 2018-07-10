<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @SWG\Swagger(
 *   @SWG\Info(
 *     title="Laravel Swaggervel",
 *     description="Operations related to functionality for laravel swaggervel",
 *     termsOfService="",
 *     @SWG\Contact(
 *       name="Company API Support",
 *       url="http://example.com/",
 *       email="no-reply@example.com"
 *     ),
 *     @SWG\License(
 *       name="MIT",
 *       url=""
 *     ),
 *     version="1.0.0"
 *   ),
 *   schemes={"https"},
 *   host="passport-swaggervel.test",
 *   basePath="/api/v1",
 *   consumes={"application/json"},
 *   produces={"application/json"},
 *   @SWG\SecurityScheme(
 *     securityDefinition="passport-swaggervel_auth",
 *     description="OAuth2 grant provided by Laravel Passport",
 *     type="oauth2",
 *     authorizationUrl="/oauth/authorize",
 *     tokenUrl="/oauth/token",
 *     flow="accessCode",
 *     scopes={
 *       *
 *     }
 *   ),
 * )
 */
class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
