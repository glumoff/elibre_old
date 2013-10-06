<?php

/**
 * Description of themeController
 *
 * @author glumoff
 */

namespace Big\ElibreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ThemeController extends Controller {

  public function showThemeAction($action, $theme) {
    //return new \Symfony\Component\HttpFoundation\Response('ssssas');
    return $this->render('BigElibreBundle:Default:theme.html.twig', array('action' => $action, 'theme' => $theme));
  }

}
