<?php

namespace spec\drupol\htmltag;

use drupol\htmltag\Attribute;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttributeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith('name', 'value');
        $this->shouldHaveType(Attribute::class);
    }

    public function it_can_trim_its_name_when_constructing()
    {
        $this->beConstructedWith('name ', 'value1 value2');
        $this->getName()->shouldBe('name');
        $this->getValueAsString()->shouldBe('value1 value2');
        $this->getValueAsArray()->shouldBe(['value1', 'value2']);
        $this->__toString()->shouldBe('name="value1 value2"');
    }

    public function it_can_replace_values()
    {
        $this->beConstructedWith('name ', 'c b a');
        $this->replace('a', 'A');
        $this->getValueAsString()->shouldBe('c b A');
        $this->getValueAsArray()->shouldBe(['c', 'b', 'A']);
        $this->__toString()->shouldBe('name="c b A"');
        $this->replace('a', 'Z');
        $this->getValueAsString()->shouldBe('c b A');
        $this->getValueAsArray()->shouldBe(['c', 'b', 'A']);
        $this->__toString()->shouldBe('name="c b A"');
    }

    public function it_can_remove_values()
    {
        $this->beConstructedWith('name ', 'c b a');
        $this->remove('a');
        $this->getValueAsString()->shouldBe('c b');
        $this->getValueAsArray()->shouldBe(['c', 'b']);
        $this->__toString()->shouldBe('name="c b"');
        $this->remove('A');
        $this->getValueAsString()->shouldBe('c b');
        $this->getValueAsArray()->shouldBe(['c', 'b']);
        $this->__toString()->shouldBe('name="c b"');
    }

    public function it_can_append()
    {
        $this->beConstructedWith('name ');
        $this->append('a');
        $this->getValueAsString()->shouldBe('a');
        $this->__toString()->shouldBe('name="a"');
        $this->append('b');
        $this->getValueAsString()->shouldBe('a b');
        $this->__toString()->shouldBe('name="a b"');
    }

    public function it_can_be_loner()
    {
        $this->beConstructedWith('name ');
        $this->append('a');
        $this->setLoner(true);
        $this->getValueAsString()->shouldBe('');
        $this->__toString()->shouldBe('name');
        $this->append('b');
        $this->getValueAsString()->shouldBe('b');
        $this->__toString()->shouldBe('name="b"');
    }

    public function it_can_merge()
    {
        $this->beConstructedWith('name ');
        $this->append('a');
        $this->merge(['a', 'b', 'c']);

        $this->getValueAsString()->shouldBe('a b c');
        $this->render()->shouldBe('name="a b c"');
    }

    public function it_can_check_if_it_contains_a_value()
    {
        $this->beConstructedWith('name ');
        $this->append('hello world');
        $this->contains('world')->shouldReturn(true);
        $this->contains('wor')->shouldReturn(true);
        $this->contains('word')->shouldReturn(false);
    }

    public function it_can_delete()
    {
        $this->beConstructedWith('name ');
        $this->append('hello world');

        $this->delete()->__toString()->shouldBe('');
    }
}
