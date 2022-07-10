<?php

namespace App\Structure\Exceptions;

use Exception;

class OnlyAcceptJsonException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'code' => 406,
            'type' => 'error',
            'message' => __('Unauthorized! Only JSON requests are allowed.')
        ], 406);
    }
}
