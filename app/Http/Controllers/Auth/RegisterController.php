<?php

namespace App\Http\Controllers\Auth;

use App\DataModel\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    public function register( Request $request )
    {
        try
        {
            $account = $request->post( 'account' );

            if ( strlen( $account ) > 20 || strlen( $account ) < 6 )
            {
                throw new \RuntimeException( '帳號字數須介於6~20位之間' );
            }

            if ( ! preg_match( '/^[A-Za-z]{1}', substr( $account, 0, 1 ) ) )
            {
                throw new \RuntimeException( '帳號首字母需大寫' );
            }

            $pwd = $request->post( 'pwd' );

            if ( strlen( $pwd ) > 20 || strlen( $pwd ) < 6 )
            {
                throw new \RuntimeException( '密碼字數須介於6~20位之間' );
            }

            if ( ! preg_match( '/^^([a-zA-Z]+\d+|\d+[a-zA-Z]+)[a-zA-Z0-9]*$/', $pwd ) )
            {
                throw new \RuntimeException( '密碼須為英數混合' );
            }

            $name = $request->post( 'name' );

            if ( ! User::where( 'account', '=', $account )->get()->isEmpty() )
            {
                throw new \RuntimeException( '帳號已存在' );
            }

            User::create( [
                'account' => $account,
                'pwd'     => md5( $pwd ),
                'name'    => $name,
            ] );

            echo json_encode( [
                'status' => true,
                'msg'    => 'success',
            ] );
        }
        catch ( \RuntimeException $e )
        {
            echo json_encode( [
                'status' => false,
                'msg'    => $e->getMessage(),
            ] );
        }
    }
}
