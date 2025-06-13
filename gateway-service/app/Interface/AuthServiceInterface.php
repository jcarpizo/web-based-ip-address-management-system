<?php

namespace App\Interface;

use Illuminate\Http\JsonResponse;

interface AuthServiceInterface
{
    public function login(string $username, string $password): JsonResponse;

    public function logout(string $token): JsonResponse;

    public function refreshToken(string $token): JsonResponse;

    public function verifyToken(string $token): JsonResponse;
}
