<?php
require 'jajjimento.php';

class JajjimentoTest extends \PHPUnit_Framework_TestCase
{
    function __construct()
    {
        $this->Jajji = new Jajjimento();
    }

    function testBasic()
    {
        $data = ['username' => 'YamiOdymel',
                 'password' => 'yamiodymel',
                 'confirm'  => 'yamiodymel',
                 'age'      => '18',
                 'url'      => 'http://teameow.com/'];

        $this->Jajji->add('username')->length(3, 12)->req()
                    ->add('password')->length(6, 30)->req()
                    ->add('confirm') ->equals('password')
                    ->add('age')->type('range')->min(1)->max(99)
                    ->add('url')->url()->req();

        $jaji->source($data)->check();
    }
}
?>