<?php

namespace App\Http\Controllers\Index;

use App\Bank;
use App\Http\Requests\Index\LoginUserRequest;
use App\Http\Requests\Index\RegisterUserRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{

    /**
     * Carg la vista principal de la web
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index.index');
    }

    /**
     * Vista de login
     *
     * @param LoginUserRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(LoginUserRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            return new JsonResponse([
                'success' => true,
                'redirect' => route('user.index'),
            ]);
        }

        return new JsonResponse(['success' => false]);
    }


    /**
     * Vista de registro
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register()
    {
        $banks = Bank::orderBy('name')->get();

        return view('index.register', ['banks' => $banks]);
    }

    /**
     * Procesa el registro de usuario
     *
     * @param RegisterUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerUser(RegisterUserRequest $request)
    {
        $user = new User($request->all());
        $user->password = bcrypt($user->password);
        $user->save();

        $this->sessionMessage('message.user.register');

        return new JsonResponse([
            'success' => true,
            'data' => $user,
            'redirect' => route('index.index'),
        ]);
    }

    /**
     * Verifica si un email esta disponible
     *
     * @param string $email
     * @return JsonResponse
     */
    public function emailExists($email)
    {
        $user = User::where('email', $email)->first();

        return new JsonResponse(['exists' => $user ? true : false]);
    }

    /**
     * Cierra la sesion
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('index.index');
    }
}
