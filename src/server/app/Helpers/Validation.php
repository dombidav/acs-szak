<?php


namespace App\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Validation
{
    /** @var array $rules */
    private $rules;
    /** @var Request $request */
    private $request;

    /**
     * Validation constructor.
     * @param array $rules
     * @param Request $request
     */
    public function __construct(Request $request, array $rules)
    {
        $this->rules = $rules;
        $this->request = $request;
    }

    public static function Define(Request $request, array $rules)
    {
        return new Validation($request, $rules);
    }

    public function Call(callable $call)
    {
        try {
            Validator::make($this->request->all(), $this->rules);
            $temp = $call();
            return response()->json($temp, 201);
        } catch (Exception $exception) {
            $data['code'] = $exception->getCode();
            $data['message'] = $exception->getMessage();
            return response()->json($data, 400);
        }
    }
}
