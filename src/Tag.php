<?php

namespace drupol\htmltag;

/**
 * Class Tag.
 */
class Tag implements TagInterface
{
    /**
     * The tag name.
     *
     * @var string
     */
    private $tag;

    /**
     * The tag attributes.
     *
     * @var \drupol\htmltag\Attributes
     */
    private $attributes;

    /**
     * The tag content.
     *
     * @var mixed[]|null
     */
    private $content;

    /**
     * Tag constructor.
     *
     * @param string $name
     *   The tag name.
     */
    public function __construct($name)
    {
        $this->tag = $name;
        $this->attributes = new Attributes();
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return \drupol\htmltag\Tag
     */
    public static function __callStatic($name, array $arguments = [])
    {
        return new static($name);
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
    public function attr($name = null, $value = null)
    {
        if (null === $name) {
            return (string) $this->attributes;
        }

        return $this->attributes[$name]->set($value);
    }

    /**
     * {@inheritdoc}
     */
    public function content($content = [])
    {
        if (false === $content) {
            $this->content = null;
            return $this->renderContent();
        }

        $content = (array) $content;

        if ([] !== $content) {
            $this->content = $content;
        }

        return $this->renderContent();
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $output = sprintf('<%s%s', $this->tag, $this->attributes);

        if (null === $this->content) {
            $output .= '/>';
        } else {
            $output .= sprintf('>%s</%s>', $this->renderContent(), $this->tag);
        }

        return $output;
    }

    /**
     * Render the tag content.
     *
     * @return string
     */
    private function renderContent()
    {
        return implode(
            '',
            array_filter(
                array_map(
                    function ($content_item) {
                        $output = '';

                        // Make sure we can 'stringify' the item.
                        if (!is_array($content_item) &&
                            ((!is_object($content_item) && settype($content_item, 'string') !== false) ||
                                (is_object($content_item) && method_exists($content_item, '__toString')))) {
                            $output = (string) $content_item;
                        }

                        return $output;
                    },
                    (array) $this->content
                ),
                'strlen'
            )
        );
    }
}
