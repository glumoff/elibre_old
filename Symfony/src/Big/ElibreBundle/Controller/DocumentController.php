<?php

namespace Big\ElibreBundle\Controller;

/**
 * Description of DocumentController
 *
 * @author Alexander Glumoff <glumoff at gmail.com>
 */
use Big\ElibreBundle\db\ElibreDBDelegate;

class DocumentController extends DefaultController {

    public function showDocAction() {
        return $this->render('BigElibreBundle:Default:doc.html.twig', $this->getTemplateParams());
    }

    protected function getTemplateParams() {
        parent::getTemplateParams();
        $request = $this->getRequest();

        $db = new ElibreDBDelegate();
        // TODO: make escaping of params
        $selectedDocID = $request->attributes->get('doc_id', 'not set');
        $doc = $db->getDocument($selectedDocID);
        if ($doc) {
            $selectedTheme = $db->getTheme($doc->getThemeID());
//            var_dump($selectedTheme);
            if ($selectedTheme) {
                $wholeThemesTree = $db->getThemes();
                $themePath = $wholeThemesTree->getThemePath($selectedTheme->getCode());
            }
        }

        $this->templateParams['doc'] = $doc;
        $this->templateParams['selectedTheme'] = $selectedTheme;
        if ($themePath) {
            $this->templateParams['activeThemeRoot'] = (count($themePath) > 0) ? $themePath[max(count($themePath) - 1, 0)]->getID() : '-1';
        }

        return $this->templateParams;
    }

}
