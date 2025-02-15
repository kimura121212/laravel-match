<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Intervention\Image\Facades\Image;
use App\Services\CheckExtensionServices;
use App\Services\FileUploadServices;


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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif|max:2000',
            'self_introduction' => 'string|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $imageFile = $data['image'];

        $list = FileUploadServices::fileUpload($imageFile); //変更

        list($extension, $fileNameToStore, $fileData) = $list; //変更

        $data_url = CheckExtensionServices::checkExtension($fileData, $extension);
        
        $image = Image::make($data_url);
        
        $image->resize(400,400)->save(storage_path() . '/app/public/images/' . $fileNameToStore );

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'self_introduction' => $data['self_introduction'],
            'sex' => $data['sex'],
            'img_name' => $fileNameToStore,
        ]);
    }
}
