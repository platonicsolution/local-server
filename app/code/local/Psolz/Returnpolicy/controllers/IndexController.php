<?php
class Psolz_Returnpolicy_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
       
            $this->loadLayout();
            $this->renderLayout();
    }
    public function abcAction(){
        echo 'yes';
         $this->loadLayout();
            $this->renderLayout();
    }
}