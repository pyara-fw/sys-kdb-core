<?php

use PHPUnit\Framework\TestCase;
use Leuffen\TextTemplate\TextTemplate;

class MyTest extends TestCase
{
    protected function getTT($tplStr)
    {
        $tt = new TextTemplate($tplStr);


        $tt->addSection("paragraph", function ($content, $params, $command, $context, $cmdParam, $self) {
            $x = str_replace(["\n","\r","\t","  "], " ", trim($content));
            return $x;
        });


        $tt->addFilter('singular_plural', function ($input, $singular=null, $plural=null, $inexistent=null) {
            $n = 0;
            if (is_array($input)) {
                $n = count($input);
            } else {
                $n = intval($input);
            }
            if ($n > 1) {
                return $plural;
            } elseif ($n == 0 && $inexistent) {
                return $inexistent;
            }
            return $singular;
        });

        $tt->addFilter('join_list_words', function ($input) {
            if (!is_array($input)) {
                return $input;
            }
            if (count($input) <= 1) {
                return reset($input);
            }
            $last = array_pop($input);
            $output = join(',', $input);
            $output .= ", and $last";
            return $output;
        });

        $tt->addFilter('ama', function ($input) {
            $article = 'an';

            $chr = strtolower(substr($input, 0, 1));
            if (strpos('aeiou', $chr) === false) {
                $article = 'a';
            }
            return "$article $input";
        });

        return $tt;
    }


    public function provider_sentence_class()
    {
        return [
            [
                ['className' => 'Engine'],
                'Engine is a class.'
            ],
            [
                [
                    'className' => 'Engine',
                    'extendsFrom' => 'Part'
                ],
                'Engine is a class that extends from class Part.'
            ],
        ];
    }



    /**
     * singular/plural based in a var, numerical token
     *      year/years
     *
     * singular/plural based in an array var
     *      is/are
     *
     * based on next word (vowel/consonant)
     *      a/am
     *
     * lists joining
     *      x / x,y, and z
     *
     * @dataProvider provider_sentence_class
     * @return void
     */
    public function test_sentence_class($data, $expected)
    {
        $tplStr = '{paragraph name="class"}{trim}
{= className}
is a class
{if extendsFrom != null }
that extends from class {=extendsFrom}
{/if}.
{/trim}{/paragraph}';

        $this->assertEquals($expected, $this->getTT($tplStr)->apply($data));
    }


    public function provider_sentence_interfaces()
    {
        return [
            [
                [
                    'interfaces' => []
                ],
                ''
            ],
            [
                [
                    'interfaces' => ['A']
                ],
                'It implements the interface A.'
            ],
            [
                [
                    'interfaces' => ['A','B']
                ],
                'It implements the interfaces A, and B.'
            ],
        ];
    }

    /**
     *
     * @dataProvider provider_sentence_interfaces
     * @return void
     */
    public function test_sentence_interfaces($data, $expected)
    {
        $tplStr = '{paragraph name="interfaces"}{trim}{if interfaces }
It implements the {=interfaces |singular_plural:interface:interfaces}
{=interfaces |join_list_words}.
{/if}{/trim}{/paragraph}';

        $tt = $this->getTT($tplStr);


        $this->assertEquals($expected, $tt->apply($data));
        // $this->assertEquals($expected, $this->getTT($tplStr)->apply ($data));
    }



    public function provider_additional()
    {
        return [
            [
                [
                    'word' => 'apple'
                ],
                'an apple'
            ],
            [
                [
                    'word' => 'banana'
                ],
                'a banana'
            ],
        ];
    }

    /**
     *
     * @dataProvider provider_additional
     * @return void
     */
    public function test_additional($data, $expected)
    {
        $tplStr = '{= word|ama}';

        $tt = $this->getTT($tplStr);
        $this->assertEquals($expected, $tt->apply($data));
    }
}
