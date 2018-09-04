<?php

namespace spec\drupol\htmltag;

use drupol\htmltag\Tag;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TagSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith('tag');
        $this->shouldHaveType(Tag::class);
    }

    public function it_should_create_a_tag()
    {
        $this->beConstructedWith('p');
        $this
        ->attr('class')
        ->append('paragraph');
        $this->__toString()
        ->shouldBe('<p class="paragraph"/>');

        $subtag = new Tag('b');
        $subtag->content(['bold text']);

        $this->content(['hello ', $subtag]);
        $this
        ->__toString()
        ->shouldBe('<p class="paragraph">hello <b>bold text</b></p>');
    }

    public function it_should_return_the_attributes_as_string()
    {
        $this->beConstructedWith('p');
        $this
            ->attr('class')
            ->append('paragraph');
        $this->attr()->shouldBe(' class="paragraph"');

        $this->__toString()
            ->shouldBe('<p class="paragraph"/>');
    }

    public function it_should_be_able_to_create_and_delete_the_content()
    {
        $this->beConstructedWith('p');
        $this->content(['hello', ' world']);
        $this->content()->shouldBe('hello world');
        $this->__toString()->shouldBe('<p>hello world</p>');

        $this->content(false)->shouldBe('');
        $this->__toString()->shouldBe('<p/>');

        $this->content([''])->shouldBe('');
        $this->__toString()->shouldBe('<p></p>');
    }

    public function it_can_be_constructed_statically()
    {
        $this->beConstructedWith('p');

        $p = $this->__callStatic('p');

        $this->render()->shouldReturn($p->render());
    }
}
