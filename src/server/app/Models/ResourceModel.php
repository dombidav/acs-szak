<?php


namespace App\Models;


use App\Traits\CamelCasing;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Class ResourceModel Base model for Resources
 * @package App\Models
 * @method static ResourceModel findOrFail($id)
 */
abstract class ResourceModel extends Model
{
    use HasFactory, Uuid, CamelCasing;

    public $usesLog = true;
    public $incrementing = false;
    protected $guarded = ['created_at', 'updated_at', 'uuid'];

    //protected $primaryKey = 'uuid';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d'
    ];
    protected $keyType = 'string';

    public static function random()
    {
        return self::query()->inRandomOrder()->get()->first->first();
    }

    public function CallCreateSchema($schemaName)
    {
        $this->CreateSchema($schemaName);
    }

    public function CreateSchema($schemaName)
    {
        $definition = $this->GetDefinition();
        Schema::create($schemaName, function (Blueprint $table) use ($definition) {
            $table->string('id')->primary();

            foreach ($definition as $key => $value) {
                $a = null;
                $rules = explode('|', $value);
                switch ($rules[0]) {
                    case 'integer':
                        $a = $table->integer($key);
                        break;
                    case 'boolean':
                        $a = $table->boolean($key);
                        break;
                    case 'numeric':
                        $a = $table->float($key);
                        break;
                    case 'timestamp':
                        $a = $table->timestamp($key);
                        break;
                    case 'string':
                    default:
                        $a = $table->string($key);
                }
                $nullable = true;
                foreach ($rules as $rule) {
                    if ($rule == 'required')
                        $nullable = false;
                    else if (Str::startsWith($rule, 'unique'))
                        $a = $a->unique();
                    else if (Str::startsWith($rule, 'default'))
                        $a = $a->default(Str::after($rule, ':'));
                }
                if ($nullable)
                    $a = $a->nullable();

                $a->comment('');
            }
            $table->timestamps();
        });
    }

    public function GetDefinition($for = null)
    {
        if (is_null($for))
            $for = array_keys($this->definition());
        else if (!is_array($for))
            $for = [$for];

        $result = [];

        foreach ($this->definition() as $key => $value) {
            if (in_array($key, $for))
                $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Defines the model
     * Every field needs a type [integer, numeric, boolean, timestamp, string]. Default type is string.
     * @return array
     */
    protected abstract function definition();
}
