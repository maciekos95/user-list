<?php

namespace Framework\Classes;

class Model
{
    /**
     * @var string The name of the dataset corresponding to the model.
     */
    protected $dataset;

    /**
     * @var array The array of fillable model attributes.
     */
    protected $fillable;

    /**
     * @var int|null The ID in the dataset.
     */
    public $id = null;

    /**
     * Magic method to dynamically set model attributes.
     *
     * @param string $name The name of the attribute.
     * @param mixed $value The value to set.
     */
    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    /**
     * Magic method to dynamically get model attributes.
     *
     * @param string $name The name of the attribute.
     * @return mixed The value of the attribute if exists, null otherwise.
     */
    public function __get(string $name): mixed
    {
        return isset($this->$name) ? $this->$name : null;
    }

    /**
     * Get the model instance for the specified ID.
     * 
     * @param int $id The ID in the dataset.
     * @return static|null The model instance if available, null otherwise.
     */
    public static function getById(int $id): ?static
    {
        $object = new static;
        $jsonPath = $object->getJsonPath();

        if ($dataset = Data::load($jsonPath)) {
            foreach ($dataset as $data) {
                if ($data['id'] == $id) {
                    $object->fromArray($data);
                    return $object;
                }
            }
        }

        return null;
    }

    /**
     * Get all model instances from the dataset.
     *
     * @param string|null $dataset The name of the dataset.
     * @return array|null An array of the model objects if available, null otherwise.
     */
    public static function getAll(string $dataset = null): ?array
    {
        $object = new static;

        if ($dataset) {
            $object->dataset = $dataset;
        }

        $objects = [];
        $jsonPath = $object->getJsonPath();

        if ($dataset = Data::load($jsonPath)) {
            foreach ($dataset as $data) {
                $object->fromArray($data);
                $objects[] = clone $object;
            }
        }

        return $objects ?? null;
    }

    /**
     * Create a new model instance from the given data array.
     *
     * @param array $data The data array for the new model instance.
     * @return static The created model instance.
     */
    public static function create(array $data): static
    {
        $object = new static;
        $object->fromArray($data);
        return $object;
    }

    /**
     * Save or update the model data from the current object to the dataset.
     *
     * @return bool True on success, false on failure.
     */
    public function save(): bool
    {
        $jsonPath = $this->getJsonPath();
        $dataset = Data::load($jsonPath);

        if ($dataset === null) {
            return false;
        }

        if (isset($this->id)) {
            foreach ($dataset as &$data) {
                if ($data['id'] == $this->id) {
                    $data = $this->toArray();
                    return Data::save($jsonPath, $dataset);
                }
            }
        } else {
            $id = 0;

            foreach ($dataset as $data) {
                if ($data['id'] > $id) {
                    $id = $data['id'];
                }
            }

            $this->id = $id + 1;
        }

        $dataset[] = $this->toArray();
        return Data::save($jsonPath, $dataset);
    }

    /**
     * Delete the current model from the dataset.
     *
     * @return bool True on success, false on failure.
     */
    public function delete(): bool
    {
        $jsonPath = $this->getJsonPath();
        $dataset = Data::load($jsonPath);

        if ($dataset === null) {
            return false;
        }

        if ($this->id) {
            foreach ($dataset as $index => $data) {
                if ($data['id'] == $this->id) {
                    unset($dataset[$index]);
                    return Data::save($jsonPath, $dataset);
                }
            }
        }

        return false;
    }

    /**
     * Set the model properties from the given array.
     *
     * @param array $dataset The data array to set the model properties.
     */
    public function fromArray(array $dataset): void
    {
        foreach ($dataset as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get the model properties as an array.
     *
     * @return array The array of the model properties.
     */
    public function toArray(): array
    {
        if (isset($this->id)) {
            $dataset['id'] = $this->id;
        }

        foreach ($this->fillable as $attribute) {
            $dataset[$attribute] = $this->$attribute;
        }

        return $dataset;
    }

    /**
     * Get the JSON file path for the dataset corresponding to the model.
     *
     * @return string The JSON file path.
     */
    protected function getJsonPath(): string
    {
        return 'dataset/' . $this->dataset . '.json';
    }
}
