<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // If already logged in as admin, redirect to dashboard
        if (session()->get('isLoggedIn') && session()->get('role') === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        // Return your Housebox login view here
        return view('auth/login'); 
    }

    public function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Verify the hashed password
            if (password_verify($password, $user->password_hash)) {
                
                // Create the session data array
                $sessionData = [
                    'user_id'    => $user->id,
                    'first_name' => $user->first_name,
                    'email'      => $user->email,
                    'role'       => $user->role,
                    'isLoggedIn' => true
                ];
                
                $session->set($sessionData);

                // Route based on role
                if ($user->role === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/dashboard'); // Seller dashboard
                }
            } else {
                $session->setFlashdata('error', 'Invalid password.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Email not found.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}