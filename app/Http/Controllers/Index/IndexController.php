<?php

namespace App\Http\Controllers\Index;

use App\Bank;
use App\DailySort;
use App\Http\Requests\Index\LoginUserRequest;
use App\Http\Requests\Index\RegisterUserRequest;
use App\Mail\PasswordResetEmail;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        $dailySorts = DailySort::all();

        return view('index.index', ['sorts' => $dailySorts]);
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

        // En caso de error busco si posee una contraseña temporal
        $user = User::where('email', $request->email)->first();

        if ($user && ! empty($user->password_temp) && $user->password_temp === $request->password) {

            // Verifico si esta vigente la contraseña temporal
            $tempExpiration = \DateTime::createFromFormat('Y-m-d H:i:s', $user->password_temp_expiration);

            if ($tempExpiration < ($now = new \DateTime())) {

                $this->sessionMessage('message.password.expired', self::ALERT_DANGER);

                return new JsonResponse([
                    'success' => true,
                    'redirect' => route('index.index'),
                ]);
            }

            // Intento autenticar con la contraseña temporal
            Auth::login($user);
            // En caso de autenticacion, expiro la contraseña temporal para que no se pueda reutilizar.
            $user->password_temp_expiration = $now;
            $user->save();

            $this->sessionMessage('message.password.temp');

            return new JsonResponse([
                'success' => true,
                'redirect' => route('user.config'),
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

        // Les doy 1000 de saldo a nuevos usuarios por promocion
        $user->balance = 1000;

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
     * Verifica si una cedula esta disponible
     *
     * @param string $identityCard
     * @return JsonResponse
     */
    public function identityCardExists($identityCard)
    {
        $user = User::where('identity_card', $identityCard)->first();

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

    /**
     * Carga la vista para recuperar contraseña
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordReset()
    {
        return view('index.passwordReset');
    }

    /**
     * Envia un correo con datos de recuperacion de contraseña
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restorePassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            $this->sessionMessage('message.email.notFound', self::ALERT_DANGER);

            return new JsonResponse(['success' => true, 'redirect' => route('index.passwordReset')]);
        }

        DB::beginTransaction();

            $user->password_temp = csrf_token();
            $user->password_temp_expiration = (new \DateTime())->modify('+1 hour');
            $user->save();

            $mail = new PasswordResetEmail([
                'token' => $user->password_temp,
                'email' => $user->email,
                'name' => $user->name,
            ]);

            Mail::send($mail);

        DB::commit();

        $this->sessionMessage('message.password.restore');

        return new JsonResponse(['success' => true, 'redirect' => route('index.index')]);
    }
}
