<?php

namespace PortedCheese\BaseSettings\Exceptions;

use Exception;
use Illuminate\Http\Request;

class PreventActionException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool
     */
    public function report()
    {
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function render(Request $request)
    {
        return $request->expectsJson() ?
            $this->invalidJson($request, $this) : $this->invalid($request, $this);
    }

    /**
     * Convert a validation exception into a response.
     *
     * @param $request
     * @param self $exception
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function invalid($request, self $exception)
    {
        return redirect(url()->previous())
            ->with("danger", $exception->getMessage());
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param $request
     * @param self $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, self $exception)
    {
        return response()->json([
            'message' => $exception->getMessage(),
        ], $exception->getCode());
    }
}
