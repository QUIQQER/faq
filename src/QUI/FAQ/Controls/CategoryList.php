<?php

/**
 * This file contains QUI\FAQ\Controls\CategoryList
 */

namespace QUI\FAQ\Controls;

use QUI;
use QUI\Exception;
use QUI\Projects\Site;
use QUI\Projects\Site\Utils;

use function boolval;

/**
 * Class CategoryList
 *
 * @author Michael Danielczok (www.pcsg.de)
 * @package QUI\FAQ\Controls\CategoryList
 */
class CategoryList extends QUI\Control
{
    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // default options
        $this->setAttributes([
            'class' => 'quiqqer-faq-control-categoryList',
            'order' => 'order_field',
            'max' => false, // max entries
            'parentSite' => null,
            'template' => 'default'
        ]);

        parent::__construct($attributes);

        $this->addCSSFile(
            dirname(__FILE__) . '/CategoryList.css'
        );

        $this->setAttribute('cacheable', 0);
    }

    /**
     * Return the inner body of the element
     * Can be overwritten
     *
     * @return String
     * @throws Exception
     */
    public function getBody(): string
    {
        $Engine = QUI::getTemplateManager()->getEngine();

        try {
            if ($this->getAttribute('parentSite') instanceof Site) {
                $FAQParentSite = $this->getAttribute('parentSite');
            } else {
                $FAQParentSite = Utils::getSiteByLink($this->getAttribute('parentSite'));
            }
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::addInfo($Exception->getMessage());

            return '';
        }

        if (!$FAQParentSite) {
            QUI\System\Log::addError('No FAQ category parent site found');
            return '';
        }

        $faqCategories = $FAQParentSite->getChildren([
            'type' => 'quiqqer/faq:types/category'
        ]);

        $template = $this->getAttribute('template');

        switch ($template) {
            case 'dummy-template':
                $html = dirname(__FILE__) . '/CategoryList.dummy.html';
                $css = dirname(__FILE__) . '/CategoryList.dummy.css';
                $this->addCSSClass('quiqqer-faq-control-categoryList--dummy');
                break;

            case 'default':
            default:
                $html = dirname(__FILE__) . '/CategoryList.default.html';
                $css = dirname(__FILE__) . '/CategoryList.default.css';
                $this->addCSSClass('quiqqer-faq-control-categoryList--default');
                break;
        }

        $this->addCSSFile($css);

        $Engine->assign([
            'this' => $this,
            'faqCategories' => $faqCategories
        ]);

        return $Engine->fetch($html);
    }
}
