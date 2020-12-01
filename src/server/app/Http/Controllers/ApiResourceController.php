<?php


namespace App\Http\Controllers;


use App\Helpers\Validation;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class ApiResourceController extends Controller
{
    protected $model;
    protected $rules;

    public function index()
    {
        /** @var Model $result */
        $result = (new $this->model);
        $result = $result::query();
        /** @var Builder $result */
        if (sizeof(\request()->query()) > 0)
            foreach (\request()->query() as $key => $value) {
                $operator = '=';
                $operatorToken = Str::before($value, '-');
                switch ($operatorToken) {
                    case 'eq':
                        $value = Str::after($value, '-');
                        break;
                    case 'lt':
                        $value = Str::after($value, '-');
                        $operator = '<';
                        break;
                    case 'gt':
                        $value = Str::after($value, '-');
                        $operator = '>';
                        break;
                    case 'lte':
                        $value = Str::after($value, '-');
                        $operator = '<=';
                        break;
                    case 'gte':
                        $value = Str::after($value, '-');
                        $operator = '>=';
                        break;
                    case 'likex':
                    case 'like':
                        $value = Str::after($value, '-');
                        $operator = 'like';
                        break;
                    default:
                        break;
                }
                if ($operatorToken = 'likex')
                    $value = "%$value%";
                $result->where($key, $operator, $value);
                if ($operator == '=')
                    $result->orWhere($key, 'like', $value);
            }
        return response()->json($result->get(), 200);
    }

    public function store(Request $request)
    {
        return Validation::Define($request, $this->rules)->Call(function () use ($request) {
            /** @var Model $temp */
            $temp = (new $this->model);
            foreach ($this->rules as $key => $value)
                $temp->$key = $request->input($key);
            $temp->save();
            return $temp;
        });
    }

    public function show($id)
    {
        $temp = $this->find($id);
        if ($temp)
            return $temp;
        return response()->json('', 404);
    }

    protected abstract function find($id);

    public function update(Request $request, $id)
    {
        $temp = $this->find($id);
        /** @var Model $temp */
        if ($temp && $request->has('name'))
            return Validation::Define($request, $this->rules)->Call(function () use ($temp, $request) {
                foreach ($this->rules as $key => $value)
                    $temp->$key = $request->input($key);
                $temp->save();
                return $temp;
            });
        return response()->json('', 404);
    }

    public function destroy($id)
    {
        /** @var Model $temp */
        $temp = $this->find($id);
        if ($temp) {
            try {
                $temp->delete();
                return response()->json($temp, 200);
            } catch (Exception $e) {
                return response()->json($e->getMessage(), $e->getCode());
            }
        }
        return response()->json('', 404);
    }
}
