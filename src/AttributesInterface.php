<?php

namespace drupol\htmltag;

/**
 * Interface AttributesInterface.
 */
interface AttributesInterface extends \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * {@inheritdoc}
     */
    public function offsetGet($name);

    /**
     * {@inheritdoc}
     */
    public function offsetSet($name, $value);

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($name);

    /**
     * {@inheritdoc}
     */
    public function offsetExists($name);

    /**
     * Import attributes.
     *
     * @param array|AttributesInterface|AttributeInterface[] $data
     *   The data to import.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function import($data = array());

    /**
     * Set an attribute.
     *
     * @param string $name
     *   The attribute name.
     * @param string|null $value
     *   The attribute value.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function set($name, $value = null);

    /**
     * Append a value into an attribute.
     *
     * @param string $key
     *   The attribute's name.
     * @param string|array $value
     *   The attribute's value.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function append($key, $value = null);

    /**
     * Remove a value from a specific attribute.
     *
     * @param string $key
     *   The attribute's name.
     * @param string|array $value
     *   The attribute's value.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function remove($key, $value = '');

    /**
     * Delete an attribute.
     *
     * @param string|array $name
     *   The name of the attribute key to delete.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function delete($name);

    /**
     * Return the attributes without a specific attribute.
     *
     * @param string $key
     *   The attributes's name.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function without($key);

    /**
     * Replace a value with another.
     *
     * @param string $key
     *   The attributes's name.
     * @param string $value
     *   The attribute's value.
     * @param array|string $replacement
     *   The replacement value.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function replace($key, $value, $replacement);

    /**
     * Merge attributes.
     *
     * @param array $data
     *   The data to merge.
     *
     * @return \drupol\htmltag\AttributesInterface
     *   The attributes.
     */
    public function merge(array $data = array());

    /**
     * Check if an attribute exists and if a value if provided check it as well.
     *
     * @param string $key
     *   Attribute name.
     * @param string $value
     *   Todo.
     *
     * @return bool
     *   Whereas an attribute exists.
     */
    public function exists($key, $value = null);

    /**
     * Check if attribute contains a value.
     *
     * @param string $key
     *   Attribute name.
     * @param string $value
     *   Attribute value.
     *
     * @return bool
     *   Whereas an attribute contains a value.
     */
    public function contains($key, $value);

    /**
     * Render the attributes.
     *
     * @return string
     *   The rendered attributes.
     */
    public function render();

    /**
     * Returns all storage elements as an array.
     *
     * @return array
     *   An associative array of attributes.
     */
    public function toArray();

    /**
     * Get storage.
     *
     * @return \drupol\htmltag\AttributeInterface[]
     *   The storage array.
     */
    public function getStorage();

    /**
     * {@inheritdoc}
     */
    public function getIterator();

    /**
     * {@inheritdoc}
     */
    public function count();
}
