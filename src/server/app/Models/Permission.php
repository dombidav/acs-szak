<?php


namespace App\Models;


class Permission extends ResourceModel
{
    public static function CreateBatch(array $array)
    {
        foreach ($array as $key => $value) {
            $p = new Permission([
                'name' => $key,
                'description' => $value
            ]);
            $p->save();
        }
    }

    /**
     * @inheritDoc
     */
    protected function definition()
    {
        return [
            'name' => 'required|min:3|max:255',
            'description' => ''
        ];
    }
}
