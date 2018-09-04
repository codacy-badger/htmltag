<?php

namespace drupol\htmltag;

/**
 * Interface TagInterface.
 */
interface TagInterface
{
    /**
     * Get the attributes as string or a specific attribute if $name is provided.
     *
     * @param string $name
     *   The name of the attribute.
     *
     * @param string $value
     *   The value.
     *
     * @return string|\drupol\htmltag\AttributeInterface
     *   The attributes as string or a specific Attribute object.
     */
    public function attr($name = null, $value = null);

    /**
     * @param array|bool $content
     *   The content.
     *
     * @return string
     *   The content.
     */
    public function content($content = []);

    /**
     * Render the tag.
     *
     * @return string
     *   The tag in html.
     */
    public function render();
}
