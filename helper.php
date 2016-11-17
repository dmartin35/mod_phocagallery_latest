<?php

defined('_JEXEC') or die;

JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');

jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_phocagallery/models', 'PhocagalleryModel');



if (! class_exists('PhocaGalleryLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocagallery/libraries/loader.php');
}
phocagalleryimport('phocagallery.ordering.ordering');
phocagalleryimport('phocagallery.pagination.paginationcategories');
phocagalleryimport('phocagallery.path.route');

use Joomla\Utilities\ArrayHelper;


abstract class ModPhocaGalleryLatestHelper
{
	/**
	 * Retrieve a list of latest phocagallery categories
	 *
	 */
	public static function getList(&$params)
	{
		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic articles model
        $model = JModelLegacy::getInstance('Categories', 'PhocagalleryModel');

		// Set application parameters in model
		$app       = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

        $limitstart =0;
        $limit = (int) $params->get('count', 5);
        
		// Set the filters based on the module params
		$model->setState('limitstart', $limitstart);
		$model->setState('list', $limit);
		
        // order by ID DESC
        $model->setState('catordering', 8);
        
        
        //shortcut to slice array - without using pagination
		$categories = $model->getData();

		$items = array_slice($categories, $limitstart, $limit);
        
		foreach ($items as &$item)
		{
            $item->link = PhocaGalleryRoute::getCategoryRoute($item->id, $item->alias);

            /*
			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			}
			else
			{
				$item->link = JRoute::_('index.php?option=com_users&view=login');
			}
            */
		}

		return $items;
	}
}
