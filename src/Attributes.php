<?php

namespace drupol\htmltag;

/**
 * Class Attributes.
 */
class Attributes implements AttributesInterface
{
    /**
     * Stores the attribute data.
     *
     * @var \drupol\htmltag\AttributeInterface[]
     */
    private $storage = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($attributes = array())
    {
        $this->import($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function import($attributes = array())
    {
        foreach ($attributes as $name => $value) {
            $this->set($name, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value = null)
    {
        $value = $value !== null ?
            $this->ensureString($value) :
            $value;

        $this->storage[$name] = new Attribute(
            $name,
            $value
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($name)
    {
        if (!isset($this->storage[$name])) {
            $this->set($name);
        }

        return $this->storage[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($name, $value = null)
    {
        $this->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($name)
    {
        unset($this->storage[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($name)
    {
        return isset($this->storage[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function append($key, $value = null)
    {
        $value = $value !== null ?
            $this->ensureString($value) :
            $value;

        $this->storage += array(
            $key => new Attribute(
                $key,
                $value
            )
        );

        foreach ($this->normalizeValue($value) as $value_item) {
            $this->storage[$key]->append($value_item);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key, $value = '')
    {
        if (!isset($this->storage[$key])) {
            return $this;
        }

        foreach ($this->normalizeValue($value) as $value_item) {
            $this->storage[$key]->remove($value_item);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($name)
    {
        foreach ($this->normalizeValue($name) as $attribute_name) {
            unset($this->storage[$attribute_name]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function without($key)
    {
        $attributes = clone $this;

        return $attributes->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, $replacement)
    {
        if (!isset($this->storage[$key])) {
            return $this;
        }

        if (!$this->contains($key, $value)) {
            return $this;
        }

        $this->storage[$key]->remove($value);
        foreach ($this->normalizeValue($replacement) as $replacement_value) {
            $this->storage[$key]->append($replacement_value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $data = array())
    {
        foreach ($data as $key => $value) {
            $this->storage += array(
                $key => new Attribute(
                    $key
                )
            );

            $this->storage[$key]->merge(
                $this->normalizeValue($value)
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key, $value = null)
    {
        if (!isset($this->storage[$key])) {
            return false;
        }

        if (null !== $value) {
            return $this->contains($key, $value);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($key, $value)
    {
        if (!isset($this->storage[$key])) {
            return false;
        }

        return $this->storage[$key]->contains($value);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $attributes = implode(' ', $this->prepareValues());

        return $attributes ? ' ' . $attributes : '';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $attributes = $this->storage;

        // If empty, just return an empty array.
        if (empty($attributes)) {
            return array();
        }

        $result = [];

        foreach ($this->prepareValues() as $attribute) {
            $result[$attribute->getName()] = $attribute->getValueAsArray();
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->storage);
    }

    /**
     * Returns all storage elements as an array.
     *
     * @return \drupol\htmltag\AttributeInterface[]
     *   An associative array of attributes.
     */
    private function prepareValues()
    {
        $attributes = $this->storage;

        // If empty, just return an empty array.
        if (empty($attributes)) {
            return array();
        }

        // Sort the attributes.
        ksort($attributes);

        $result = [];

        foreach ($attributes as $attribute_name => $attribute) {
            switch ($attribute_name) {
                case 'class':
                    $classes = $attribute->getValueAsArray();
                    asort($classes);
                    $result[$attribute->getName()] = $attribute->set(
                        implode(' ', $classes)
                    );
                    break;

                default:
                    $result[$attribute->getName()] = $attribute;
            }
        }

        return $result;
    }

    /**
     * Normalize a value.
     *
     * @param mixed $value
     *  The value to normalize.
     *
     * @return array
     *   The value normalized.
     */
    private function normalizeValue($value)
    {
        return $this->ensureFlatArray($value);
    }

    /**
     * Todo.
     *
     * @param mixed $value
     *   Todo.
     *
     * @return array
     *   The array, flattened.
     */
    private function ensureFlatArray($value)
    {
        switch (gettype($value)) {
            case 'string':
                $return = explode(
                    ' ',
                    $this->ensureString($value)
                );
                break;

            case 'array':
                $flat_array = iterator_to_array(
                    new \RecursiveIteratorIterator(
                        new \RecursiveArrayIterator(
                            $value
                        )
                    ),
                    false
                );

                $return = [];
                foreach ($flat_array as $item) {
                    $return = array_merge(
                        $return,
                        $this->normalizeValue($item)
                    );
                }
                break;

            case 'double':
            case 'integer':
                $return = array($value);
                break;
            case 'object':
            case 'boolean':
            case 'resource':
            case 'NULL':
            default:
                $return = array();
                break;
        }

        return $return;
    }

    /**
     * Todo.
     *
     * @param mixed $value
     *   Todo.
     *
     * @return string
     *   A string.
     */
    private function ensureString($value)
    {
        switch (gettype($value)) {
            case 'string':
                $return = $value;
                break;

            case 'array':
                $return = implode(
                    ' ',
                    $this->ensureFlatArray($value)
                );
                break;

            case 'double':
            case 'integer':
                $return = (string) $value;
                break;
            case 'object':
            case 'boolean':
            case 'resource':
            case 'NULL':
            default:
                $return = '';
                break;
        }

        return $return;
    }
}
