<?php
/**
 * Example helper for the framework.
 * For an example usage, please see {@link welcomeController::index()}.
 * @author Bruno Jorge <brunojorge11@gmail.com>
 * @package frameworkCore
 * @subpackage helpers
 */
class menuHelper extends appCore {
    
    /**
     * Adds "menu" template to the returned view.
     */
    public function getMenu(){
	$this->view()->addTemplate("menu");
    }
    
}