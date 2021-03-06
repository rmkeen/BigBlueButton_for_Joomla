<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    17th July, 2018
 * @author     Jibon L. Costa <https://www.hoicoimasti.com>
 * @github     Joomla Component Builder <https://github.com/vdm-io/Joomla-Component-Builder>
 * @copyright  Copyright (C) 2019 Hoicoi Extension. All Rights Reserved
 * @license    MIT
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Events Form Field class for the Bigbluebutton component
 */
class JFormFieldEvents extends JFormFieldList
{
	/**
	 * The events field type.
	 *
	 * @var		string
	 */
	public $type = 'events';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db = JFactory::getDBO();
$query = $db->getQuery(true);
$query->select($db->quoteName(array('a.id','a.title'),array('id','meeting_id_title')));
$query->from($db->quoteName('#__bigbluebutton_meeting', 'a'));
$query->where($db->quoteName('a.published') . ' = 1');
$query->order('a.title ASC');
$db->setQuery((string)$query);
$items = $db->loadObjectList();
$options = array();
if ($items)
{
	$options[] = JHtml::_('select.option', '', 'Select an option');
	foreach($items as $item)
	{
		$options[] = JHtml::_('select.option', $item->id, $item->meeting_id_title);
	}
}

return $options;
	}
}
