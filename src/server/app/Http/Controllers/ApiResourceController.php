<?php


namespace App\Http\Controllers;


use App\Events\CrudEvents\Successful\ResourceModelCreatedEvent;
use App\Events\CrudEvents\Successful\ResourceModelUpdatedEvent;
use App\Events\CrudEvents\Successful\ResourceModelDeletedEvent;
use App\Events\CrudEvents\Failed\ResourceModelFailedToCreateEvent;
use App\Events\CrudEvents\Failed\ResourceModelFailedToUpdateEvent;
use App\Events\CrudEvents\Failed\ResourceModelFailedToDeleteEvent;
use App\Helpers\LogHelper;
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
        $user_name = Auth::check() ? Auth::user()->name : 'Guest';
        LogHelper::Notify((new $this->model)->getTable() . ' was accessed by ' . $user_name);
        return response()->json($results, 200);
    }

    protected function beforeStore(){}
    protected function afterStore($result){}

    public function store(Request $request)
    {
        $this->beforeStore();
        return Validation::Define($request, $this->rules)->Call(function () use ($request) {
            /** @var ResourceModel $temp */
            $temp = (new $this->model);
            foreach ($this->rules as $key => $value)
                $temp->$key = $request->input($key);
            $temp->save();
            $this->afterStore($temp);
            event(new ResourceModelCreatedEvent($temp));
            return $temp;
        })->Otherwise(function ($error){
            event(new ResourceModelFailedToCreateEvent($error, (new $this->model)));
        })->response;
    }

    protected function beforeShow($id){}
    protected function afterShow($result){}

    public function show($id)
    {
        $this->beforeShow($id);
        /** @var ResourceModel $temp */
        $temp = $this->find($id);
        if ($temp) {
            $user_name = Auth::check() ? Auth::user()->name : 'Guest';
            LogHelper::Notify((new $this->model)->getTable() . ' with id "' . $temp->getKey() . '" was accessed by ' . $user_name);
            return $temp;
        }
        $this->afterShow($temp);
        return response()->json('', 404);
    }

    protected abstract function find($id);

    protected function beforeUpdate($id){}
    protected function afterUpdate($result){}

    public function update(Request $request, $id)
    {
        $this->beforeUpdate($id);
        $temp = $this->find($id);
        /** @var ResourceModel $temp */
        if ($temp)
            return Validation::Define($request, $this->rules)->Call(function () use ($temp, $request) {
                $original = $temp;
                foreach ($this->rules as $key => $value)
                    $temp->$key = $request->input($key);
                $temp->save();
                event(new ResourceModelUpdatedEvent($temp, $original));
                return $temp;
            })->Otherwise(function ($error) use ($temp) {
                event(new ResourceModelFailedToUpdateEvent($error, $temp));
            })->response;
        $this->afterUpdate($temp);
        return response()->json('', 404);
    }

    protected function beforeDestroy($id){}
    protected function afterDestroy($result){}

    public function destroy($id)
    {
        $this->beforeDestroy($id);
        /** @var ResourceModel $temp */
        $temp = $this->find($id);
        if ($temp) {
            try {
                $original = $temp;
                $temp->delete();
                event(new ResourceModelDeletedEvent($original));
                return response()->json($temp, 200);
            } catch (Exception $e) {
                event(new ResourceModelFailedToDeleteEvent($e, $temp));
                return response()->json($e->getMessage(), $e->getCode());
            }
        }
        $this->afterDestroy($temp);
        return response()->json('', 404);
    }
}
