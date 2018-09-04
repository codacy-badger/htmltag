<?php

namespace spec\drupol\htmltag;

use drupol\htmltag\Attributes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttributesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Attributes::class);
    }

    public function it_should_append_attribute_and_value()
    {
        $this
            ->append('class', 'plop')
            ->__toString()
            ->shouldBe(' class="plop"');
    }

    public function it_should_append_loner_attribute()
    {
        $this
            ->append('data-closable')
            ->__toString()
            ->shouldBe(' data-closable');

        $this
            ->append('data-closable')
            ->append('data-closable')
            ->__toString()
            ->shouldBe(' data-closable');
    }

    public function it_can_append_and_remove()
    {
        $this
            ->append('class', ['foo', 'bar'])
            ->render()
            ->shouldBe(' class="bar foo"');

        $this
            ->append('data-closable')
            ->append('class', 'fool')
            ->render()
            ->shouldBe(' class="bar foo fool" data-closable');

        $this
            ->remove('class', 'fool')
            ->__toString()
            ->shouldBe(' class="bar foo" data-closable');

        $this
            ->remove('class', 'foo')
            ->remove('class', 'bar')
            ->__toString()
            ->shouldBe(' class="" data-closable');

        $this
            ->set('class', null)
            ->__toString()
            ->shouldBe(' class="" data-closable');

        $this
            ->delete('class')
            ->delete('data-closable')
            ->__toString()
            ->shouldBe('');
    }

    public function it_can_remove_a_non_existing_attribute()
    {
        $this->remove('unexisting')->shouldBe($this);
    }

    public function it_can_replace_attribute()
    {
        $this
            ->append('data-closable')
            ->append('class', 'foo');

        $this
            ->replace('class', 'foo', 'bar fool');

        $this
            ->replace('class', 'plop', 'plip');

        $this
            ->replace('unknown', 'foo', 'bar fool');

        $this
            ->render()
            ->shouldBe(' class="bar fool" data-closable');
    }

    public function it_can_merge_data()
    {
        $this
            ->append('data-closable')
            ->append('class', 'b');

        $this
            ->merge(
                [
                    'class' => ['a c'],
                    'empty1' => [],
                    'loner' => [''],
                    'data-popup' => null,
                    'foo' => 'bar',
                ]
            );

        $this
            ->render()
            ->shouldBe(' class="a b c" data-closable data-popup empty1 foo="bar" loner=""');
    }

    public function it_can_check_if_an_attribute_exists()
    {
        $this
            ->append('data-closable')
            ->append('class', 'b');

        $this
            ->exists('class')
            ->shouldReturn(true);
        $this
            ->exists('XclassX')
            ->shouldReturn(false);

        $this
            ->exists('class', 'b')
            ->shouldReturn(true);
        $this
            ->exists('class', 'c')
            ->shouldReturn(false);

        $this
            ->exists('XclassX', 'c')
            ->shouldReturn(false);

        $this
            ->exists('data-closable')
            ->shouldReturn(true);
    }

    public function it_can_check_if_an_attribute_contains_a_value()
    {
        $this
            ->append('data-closable')
            ->append('class', 'b');

        $this
            ->contains('class', 'b')
            ->shouldReturn(true);

        $this
            ->contains('XclassX', 'b')
            ->shouldReturn(false);
    }

    public function it_can_count()
    {
        $this
            ->count()
            ->shouldReturn(0);

        $this
            ->append('data-closable')
            ->append('class', 'b');

        $this
            ->count()
            ->shouldReturn(2);
    }

    public function it_can_return_array()
    {
        $this
            ->toArray()
            ->shouldReturn(array());

        $this
            ->append('data-closable')
            ->append('class', 'b')
            ->append('class', 'a c');

        $expected = array(
            'class' => array(
                'a',
                'b',
                'c',
            ),
            'data-closable' => array(),
        );

        $this->toArray()->shouldReturn($expected);
    }

    public function it_can_return_an_iterator()
    {
        $this
            ->getIterator()
            ->shouldReturnAnInstanceOf(\Iterator::class);
    }

    public function it_can_return_the_storage()
    {
        $this
            ->getStorage()
            ->shouldReturn(array());

        $this
            ->append('data-closable')
            ->append('class', 'b')
            ->append('class', 'a c');

        $this
            ->getStorage()
            ->shouldBeArray();
    }

    public function it_can_render()
    {
        $this
            ->render()
            ->shouldReturn('');

        $this
            ->append('data-closable')
            ->append('class', 'b')
            ->append('class', 'a c')
            ->append('id', 123)
            ->render()
            ->shouldBe(' class="a b c" data-closable id="123"');
    }

    public function it_has_working_offsetexists()
    {
        $this
            ->append('data-closable')
            ->append('class', 'b')
            ->append('class', 'a c');

        $this->offsetExists('class')->shouldReturn(true);
        $this->offsetExists('unknown')->shouldReturn(false);
        $this->offsetExists('data-closable')->shouldReturn(true);
    }

    public function it_has_working_offsetunset()
    {
        $this
            ->append('data-closable')
            ->append('class', 'b')
            ->append('class', 'a c');

        $this->offsetUnset('class');
        $this->offsetUnset('unknown');
        $this->offsetExists('class')->shouldReturn(false);
        $this->offsetExists('unknown')->shouldReturn(false);
    }

    public function it_has_working_offsetset()
    {
        $this
            ->append('data-closable')
            ->append('class', 'b')
            ->append('class', 'a c');

        $this->offsetSet('class', 'foo');

        $this
            ->render()
            ->shouldReturn(' class="foo" data-closable');
    }
    
    public function it_has_working_constructor()
    {
        $this->beConstructedWith([
            'class' => 'a',
            'class_array' => ['a', 'b', 'c'],
            'data-popup' => null,
            'data-popup-array' => [null, null, null],
            'integer' => 1,
            'integer_array' => [1, 2, 3],
            'double' => 3.141516,
            'double_array' => [3.141516, 2.71828182845],
            'bool' => true,
            'bool_array' => [true, false, true],
            'object' => new \stdClass(),
            'object_array' => [new \stdClass(), new \stdClass(), new \stdClass()],
            'null' => null,
            'null_array' => [null, null, null],
        ]);
        
        $this
            ->render()
            ->shouldReturn(' bool="" bool_array="" class="a" class_array="a b c" data-popup data-popup-array="" double="3.141516" double_array="3.141516 2.71828182845" integer="1" integer_array="1 2 3" null null_array="" object="" object_array=""');
    }

    public function it_can_return_attributes_without_a_specific_attribute()
    {
        $data =                 [
            'class' => ['a', 'b', 'c'],
            'data-popup' => null
        ];

        $this
            ->beConstructedWith($data);

        $this
            ->without('class')
            ->render()
            ->shouldReturn(' data-popup');
    }

    public function it_can_set()
    {
        $data =                 [
            'class' => ['a', 'b', 'c'],
            'data-popup' => null
        ];

        $this
            ->beConstructedWith($data);


        $this
            ->set('class', 'foo')
            ->set('data-bar')
            ->render()
            ->shouldReturn(' class="foo" data-bar data-popup');
    }

    public function it_can_import()
    {
        $data =                 [
            'class' => ['a', 'b', 'c'],
            'data-popup' => null,
            'foo' => 'bar',
        ];

        $this
            ->beConstructedWith($data);

        $import = array(
            'class' => ['plop', 'foo'],
            'data-popup' => null,
            'data-test' => array(
                'b',
                'a c'
            ),
        );

        $this
            ->import($import)
            ->render()
            ->shouldReturn(' class="foo plop" data-popup data-test="b a c" foo="bar"');
    }
}
