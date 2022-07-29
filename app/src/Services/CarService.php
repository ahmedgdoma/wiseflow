<?php


namespace App\Services;

class CarService
{
    const VAR_TYPES = [
        'model' => 'intval',
        'gasEconomy' => 'boolval',
        'color' => 'strval'
    ];

    /**
     * @param array $parameters
     * @return array
     */
    public function filterParameters(array $parameters): array
    {
        $sort = [];
        $filters = [];
        if (in_array('sort', array_keys($parameters))) {
            $sort = $parameters['sort'];
            unset($parameters['sort']);
        }
        foreach ($parameters as $key => $value) {
            if (!empty(self::VAR_TYPES[$key])) {
                $filters[$key] = $this->getValueTypes($value, self::VAR_TYPES[$key]);
            }
        }
        return [
            'filters' => $filters,
            'sort' => $sort,
        ];
    }

    /**
     * @param $value
     * @param string $function
     * @return array
     */
    private function getValueTypes($value, string $function): array
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = call_user_func($function, $val);
            }
            return $value;
        }
        return ['eq' => call_user_func($function, $value)];
    }

    /**
     * @param array $cars
     * @return array
     */
    public function serializeCars(array $cars): array
    {
        $data = [];
        foreach ($cars as $car) {
            $serializedCar = [];
            $serializedCar['id'] = $car->getId();
            $serializedCar['model'] = $car->getModel();
            $serializedCar['color'] = $car->getColor();
            $serializedCar['gasEconomy'] = $car->isGasEconomy();
            $serializedCar['brand'] = $car->getBrand()->getName();
            foreach ($car->getAccessories() as $accessory) {
                $serializedCar['accessories'][] = $accessory->getName();
            }
            $data[] = $serializedCar;
        }

        return $data;
    }
}