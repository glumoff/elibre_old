<?php

namespace Big\ElibreBundle\Controller;

/**
 * Description of SearchController
 *
 * @author Alexander Glumoff <glumoff at gmail.com>
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Big\ElibreBundle\db\ElibreDBDelegate;

class SearchController extends Controller {

  protected $templateParams = NULL;

  public function indexAction() {
    $response = $this->render('BigElibreBundle:Default:search.html.twig', $this->getTemplateParams());
    return $response;
  }

  protected function getTemplateParams() {
    $this->get('translator')->setLocale($this->getRequest()->getPreferredLanguage());

    if ($this->templateParams === NULL) {
      $this->templateParams = array();
      $this->templateParams['menuThemes'] = $this->getThemesMenuArray();

      $needle = $this->getRequest()->query->get('needle');
      $this->templateParams['needle'] = $needle;
      if ($needle) {
        $this->templateParams['results'] = $this->getResults($needle);
      }

//      $this->templateParams['needle'] = $this->getRequest()->request->get('needle');
//      var_dump($this->templateParams);
    }
    return $this->templateParams;
  }

  protected function getThemesMenuArray() {
    $db = new ElibreDBDelegate();
    $themesList = $db->getThemes(2);
    return $themesList->getThemesArray();
  }

  protected function getResults($needle) {
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery(
                    'SELECT d
                      FROM BigElibreBundle:Document d
                      WHERE d.title LIKE :needle
                         OR d.tags LIKE :needle
                         OR d.path LIKE :needle
                      ORDER BY d.title ASC'
            )->setParameter('needle', '%' . $needle . '%');
    $docs = $query->getResult();
    return $docs;
  }

}
