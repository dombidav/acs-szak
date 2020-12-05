<?php


namespace App\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Validation
{
    /** @var array $rules */
    private $rules;
    /** @var Request $request */
    private $request;
    /**
     * @var JsonResponse
     */
    public $response;
    /**
     * @var bool
     */
    private $hasError = false;

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
            $this->response = response()->json($temp, 201);
        } catch (Exception $exception) {
            $data['code'] = $exception->getCode();
            $data['message'] = $exception->getMessage();
            $this->hasError = true;
            $this->response = response()->json($data, 400);
        }
        return $this;
    }

    public function Otherwise(callable  $call)
    {
        $temp = $call($this->response->getContent());
        return $this;
    }
}
