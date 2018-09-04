<?php

namespace drupol\htmltag;

/**
 * Interface AttributeInterface.
 */
interface AttributeInterface
{
    /**
     * Get the attribute name.
     *
     * @return string
     *   The attribute name.
     */
    public function getName();

    /**
     * Get the attribute value as a string.
     *
     * @return string
     *   The attribute value as a string.
     */
    public function getValueAsString();

    /**
     * Get the attribute value as an array.
     *
     * @return array
     *   The attribute value as an array.
     */
    public function getValueAsArray();

    /**
     * Convert the object in a string.
     *
     * @return string
     *   The attribute as a string.
     */
    public function render();

    /**
     * Check if the attribute is a loner attribute.
     *
     * @return bool
     *   True or False.
     */
    public function isLoner();

    /**
     * Set the value.
     *
     * @param string|null $value
     *   The value.
     *
     * @return \drupol\htmltag\AttributeInterface
     */
    public function set($value = null);

    /**
     * Append a value to the attribute.
     *
     * @param string $value
     *   The value to append.
     *
     * @return \drupol\htmltag\AttributeInterface
     *   The attribute.
     */
    public function append($value);

    /**
     * Merge data into the attribute value.
     *
     * @param array $values
     *   The values to merge.
     *
     * @return \drupol\htmltag\AttributeInterface
     *   The attribute.
     */
    public function merge(array $values);

    /**
     * Remove a value from the attribute.
     *
     * @param string $value
     *   The value to remove.
     *
     * @return \drupol\htmltag\AttributeInterface
     *   The attribute.
     */
    public function remove($value);

    /**
     * Replace a value of the attribute.
     *
     * @param string $original
     *   The original value.
     * @param string $replacement
     *   The replacement value.
     *
     * @return \drupol\htmltag\AttributeInterface
     *   The attribute.
     */
    public function replace($original, $replacement);

    /**
     * Check if the attribute contains a string or a substring.
     *
     * @param string $substring
     *   The string to check.
     *
     * @return bool
     *   True or False.
     */
    public function contains($substring);

    /**
     * Set the attribute as a loner attribute.
     *
     * @param bool $loner
     *   True or False.
     *
     * @return \drupol\htmltag\AttributeInterface
     *   The attribute.
     */
    public function setLoner($loner = true);

    /**
     * Delete the current attribute.
     *
     * @return \drupol\htmltag\AttributeInterface
     *   The attribute.
     */
    public function delete();
}
