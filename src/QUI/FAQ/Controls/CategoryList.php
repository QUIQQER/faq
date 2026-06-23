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
            'template' => 'default',
            'subpages' => '', // '' / categories / all
            'showImage' => false,
            'imageWidth' => 'all', // any css valid value
            'imageAlignment' => 'center', // flex-start / center / flex-end
            'iconSize' => 'normal',
            'showTitle' => true,
            'showDesc' => true,
            'textAlignment' => 'left', // left / center / right
            'showButton' => true,
            'btnAlignment' => 'stretch', // stretch / flex-start / center / flex-end
            'btnCssClass' => ''
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

        $faqCategories = $FAQParentSite->getChildren([
            'type' => 'quiqqer/faq:types/category'
        ]);


        // btn alignment
        $btnAlignment = match ($this->getAttribute('btnAlignment')) {
            'left' => 'flex-start',
            'center' => 'center',
            'right' => 'flex-end',
            default => 'stretch'
        };

        $this->setStyle('--settings--btnAlignment', $btnAlignment);

        // image
        $imageWidth = $this->getAttribute('imageWidth');
        $imageAlignment = match ($this->getAttribute('imageAlignment')) {
            'left' => 'flex-start',
            'right' => 'flex-end',
            default => 'center'
        };
        $iconSize = match ($this->getAttribute('iconSize')) {
            'small' => '1.5rem',
            'large' => '5rem',
            'x-large' => '8rem',
            default => '2.5rem'
        };

        if ($imageWidth) {
            $this->setStyle('--settings--imageWidth', $imageWidth);
        }

        $this->setStyle('--settings--imageAlignment', $imageAlignment);
        $this->setStyle('--settings--iconSize', $iconSize);

        // text position
        [$textFlexboxAlignment, $textAlignment] = match ($this->getAttribute('textAlignment')) {
            'left' => ['flex-start', 'left'],
            'right' => ['flex-end', 'right'],
            default => ['center', 'center']
        };

        $this->setStyle('--settings--textFlexboxAlignment', $textFlexboxAlignment);
        $this->setStyle('--settings--textAlignment', $textAlignment);

        // subpages
        $types = [];
        $subpages = '';
        switch ($this->getAttribute('subpages')) {
            case 'categories':
                $subpages = 'categories';
                $types = ['quiqqer/faq:types/list2025'];
                break;
            case 'all':
                $subpages = 'all';
                $types = ['quiqqer/faq:types/list2025', 'quiqqer/faq:types/category'];
                break;
        }

        // template
        $template = $this->getAttribute('template');

        switch ($template) {
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
            'faqCategories' => $faqCategories,
            'showImage' => $this->getAttribute('showImage'),
            'showTitle' => $this->getAttribute('showTitle'),
            'showDesc' => $this->getAttribute('showDesc'),
            'showButton' => $this->getAttribute('showButton'),
            'btnAlignment' => $btnAlignment,
            'btnCssClass' => $this->getAttribute('btnCssClass'),
            'subpages' => $subpages,
            'types' => $types
        ]);

        return $Engine->fetch($html);
    }
}
