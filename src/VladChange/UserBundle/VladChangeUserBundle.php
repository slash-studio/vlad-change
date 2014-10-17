<?php

namespace VladChange\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VladChangeUserBundle extends Bundle
{
   public function getParent()
   {
      return 'FOSUserBundle';
   }
}
