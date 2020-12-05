<?php


namespace App\Http\Controllers;


use app\Helpers\LogHelper;
use App\Helpers\Validation;
use App\Models\ResourceModel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

abstract class ApiResourceController extends Controller
{
    /** @var ResourceModel $model */
    protected $model;
    protected $rules;

    protected function beforeIndex(){}
    protected function afterIndex($results){}

    public function index()
    {
        $this->beforeIndex();
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
        $results = $result->get();
        $this->afterIndex($results);
        LogHelper::Notify($this->model->getTable() . ' was accessed by ' . Auth::user()->name);
        return response()->json($results, 200);
    }

    protected function beforeStore(){}
    protected function afterStore($result){}

    public function store(Request $request)
    {
        $this->beforeStore();
        return Validation::Define($request, $this->rules)->Call(function () use ($request) {
            /** @var Model $temp */
            $temp = (new $this->model);
            foreach ($this->rules as $key => $value)
                $temp->$key = $request->input($key);
            $temp->save();
            $this->afterStore($temp);
            LogHelper::Notify('New ' . $this->model->getTable() . ' was created by ' . Auth::user()->name . ' with id "' . $temp->getKey() . '"');
            return $temp;
        })->Otherwise(function ($error){
            LogHelper::Error('New ' . $this->model->getTable() . ' was unsuccessfully created by ' . Auth::user()->name . ' error was "' . $error . '"');
        })->response;
    }

    protected function beforeShow($id){}
    protected function afterShow($result){}

    public function show($id)
    {
        $this->beforeShow($id);
        /** @var ResourceModel $temp */
        $temp = $this->find($id);
        if ($temp)
            return $temp;
        $this->afterShow($temp);
        LogHelper::Notify($this->model->getTable() . ' with id "' . $temp->getKey() . '" was accessed by ' . Auth::user()->name);
        return response()->json('', 404);
    }

    protected abstract function find($id);

    protected function beforeUpdate($id){}
    protected function afterUpdate($result){}

    public function update(Request $request, $id)
    {
        $this->beforeUpdate($id);
        $temp = $this->find($id);
        /** @var Model $temp */
        if ($temp)
            return Validation::Define($request, $this->rules)->Call(function () use ($temp, $request) {
                $original = $temp->jsonSerialize();
                foreach ($this->rules as $key => $value)
                    $temp->$key = $request->input($key);
                $temp->save();
                LogHelper::Notify($this->model->getTable() . ' with id "' . $temp->getKey() . '" was modified by ' . Auth::user()->name . 'Original: /"' . $original . '"/');
                return $temp;
            })->Otherwise(function ($error) use ($temp) {
                LogHelper::Error($this->model->getTable() . ' with id "' . $temp->getKey() . '" was unsuccessfully modified by ' . Auth::user()->name . 'error was: /"' . $error . '"/');
            })->response;
        $this->afterUpdate($temp);
        return response()->json('', 404);
    }

    protected function beforeDestroy($id){}
    protected function afterDestroy($result){}

    public function destroy($id)
    {
        $this->beforeDestroy($id);
        /** @var Model $temp */
        $temp = $this->find($id);
        if ($temp) {
            try {
                $temp->delete();
                LogHelper::Notify($this->model->getTable() . ' with id "' . $temp->getKey() . '" was deleted by ' . Auth::user()->name . 'Original: /"' . $temp->jsonSerialize() . '"/');
                return response()->json($temp, 200);
            } catch (Exception $e) {
                LogHelper::Error($this->model->getTable() . ' with id "' . $temp->getKey() . '" was unsuccessfully deleted by ' . Auth::user()->name . 'error was: /"' . $e->getMessage() . '"/');
                return response()->json($e->getMessage(), $e->getCode());
            }
        }
        $this->afterDestroy($temp);
        return response()->json('', 404);
    }
}
