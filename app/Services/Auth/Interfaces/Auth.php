<?php

interface Auth {
    /**
     * Login method based on user type
     * 
     * @param Request $request
     * @return Response $response
     */
    public function login($request);

    /**
     * Validate login based on user type
     * 
     * @param $request
     * @return bool $valid
     */
    public function validateLoginRequest($request):bool;

    /**
     * Register method
     * 
     * @param Request $request
     * @return Response $response
     */
    // public function register($request);
}