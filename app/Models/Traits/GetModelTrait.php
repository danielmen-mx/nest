<?php

namespace App\Models\Traits;

use App\Facades\Conversion;

trait GetModelTrait
{
    public function getModel($modelType, $identifier)
    {
        $model = $this->getModelClass($modelType);
        if (!$this->isUuid($identifier)) {
            $identifier = Conversion::idToUuid($identifier, $model);
        }

        return $model::where('uuid', $identifier)->firstOrFail();
    }

    public function getModelName($modelType)
    {
        $class = explode("\\", $modelType);

        return ucfirst(end($class));
    }

    public function getModelClass($modelType)
    {        
        $class = explode("\\", $modelType);

        return 'App\\Models\\Cupboard\\' . ucfirst(end($class));
    }

    protected function isUuid($id)
    {
        return !is_numeric($id);
    }
}
