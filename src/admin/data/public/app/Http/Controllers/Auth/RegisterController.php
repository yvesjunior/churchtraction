<?php

namespace App\Http\Controllers\Auth;

use App\Field;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/groups';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        if(setting('general_enable_registration')!=1){
            exit(__('site.registration-disabled'));
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'gender'=>['required'],
            'telephone'=>['required']
        ];

        $messages = [];
        foreach(Field::where('enabled',1)->orderBy('sort_order','asc')->get() as $field){
            if($field->required==1)
            {
                $rules['field_'.$field->id] = ['required'];
            }
            $messages['field_'.$field->id.'.required'] = __('site.field-required-msg',['field'=>$field->name]);
        }

        return Validator::make($data, $rules,$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        if(!empty($data['f_telephone'])){
            $data['telephone'] = $data['f_telephone'];
        }

        $user= User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'=> 2,
            'telephone'=>getPhoneNumber($data['telephone']),
            'gender'=>$data['gender'],
        ]);

        $customValues = [];
        //attach custom values
        foreach(Field::where('enabled',1)->orderBy('sort_order','asc')->get() as $field){
            if(isset($data['field_'.$field->id]))
            {
                $customValues[$field->id] = ['value'=>$data['field_'.$field->id]];
            }


        }


        $user->fields()->attach($customValues);

        return $user;
    }

    public function register(Request $request)
    {
        try {
            return $this->registerAttempt($request);
        } catch (ValidationException $e) {
            // Copied from https://github.com/laravel/framework/blob/5.3/src/Illuminate/Foundation/Exceptions/Handler.php
            if ($e->response) {
                return $e->response;
            }

            $errors = $e->validator->errors()->getMessages();

            if ($request->expectsJson()) {
                return response()->json($errors, 422);
            }

            return redirect('/register')->withInput($request->input())->withErrors($errors);
        }
    }

    public function registerAttempt(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }


}
