<?php

namespace drupol\htmltag;

/**
 * Class Attribute.
 */
class Attribute implements AttributeInterface
{
    /**
     * Store the attribute name.
     *
     * @var string
     */
    private $name;

    /**
     * Store the attribute value.
     *
     * @var array|null
     */
    private $values;

    /**
     * Attribute constructor.
     *
     * @param string $name
     *   The attribute name.
     * @param string $value
     *   The attribute value.
     */
    public function __construct($name, $value = null)
    {
        $this->name = trim($name);
        $this->set($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getValueAsString()
    {
        return implode(
            ' ',
            $this->getValueAsArray()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getValueAsArray()
    {
        return array_values(
            array_unique(
                (array) $this->values
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $output = $this->name;

        if (!$this->isLoner()) {
            $output .= '="' . trim($this->getValueAsString()) . '"';
        }

        return $output;
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
    public function isLoner()
    {
        if ([] === $this->getValueAsArray()) {
            $this->values = null;
        }

        return null === $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function set($value = null)
    {
        if (null !== $value) {
            $this->values = explode(' ', trim($value));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function append($value)
    {
        $this->values = array_merge(
            (array) $this->values,
            explode(
                ' ',
                trim($value)
            )
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $values)
    {
        $this->values = array_merge(
            (array) $this->values,
            $values
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($value)
    {
        $this->values = array_filter(
            (array) $this->values,
            function ($value_item) use ($value) {
                return $value_item !== $value;
            }
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function replace($original, $replacement)
    {
        $this->values = array_map(
            function (&$value_item) use ($original, $replacement) {
                if ($value_item === $original) {
                    $value_item = $replacement;
                }

                return $value_item;
            },
            (array) $this->values
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($substring)
    {
        foreach ((array) $this->values as $value_item) {
            if (false !== strpos($value_item, $substring)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setLoner($loner = true)
    {
        if (true === $loner) {
            $this->values = null;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $this->name = '';
        $this->values = null;

        return $this;
    }
}
