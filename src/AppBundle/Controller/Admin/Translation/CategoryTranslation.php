<?php

namespace AppBundle\Controller\Admin\Translation;

use AppBundle\Entity\Translation\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class CategoryTranslation extends BaseAdminController
{
    /**
     * @param  \AppBundle\Entity\Translation\CategoryTranslation $categoryTranslation
     */
    protected function prePersistEntity($categoryTranslation)
    {
        $category = new Category();
        $categoryTranslation->setTranslatable($category);
        $category->mergeNewTranslations();
    }
}
