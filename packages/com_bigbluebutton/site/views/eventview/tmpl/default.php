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

?>
<?php echo $this->toolbar->render(); ?>
<?php
$doc = JFactory::getDocument();
$doc->addStyleSheet('media/jui/css/bootstrap.min.css');

if($this->item->timezone == 1){
	$config = JFactory::getConfig();
	$timeZone = $config->get('offset');
} else{
	$timeZone = $this->item->event_timezone;
}
$dtz = new DateTimeZone($timeZone);
$time_in_sofia = new DateTime('now', $dtz);
$offset = $dtz->getOffset($time_in_sofia) / 3600;

$GMT = $timeZone." (GMT" . ($offset < 0 ? $offset : "+" . $offset).")";

$doc->addScriptDeclaration('
	jQuery("document").ready(function($){
		$("#bbbLoginFrom").submit(function(e){
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				method: "GET",
				dataType: "jsonp",
				url: "index.php?"+data,
				jsonp: "callback",
				
				beforeSend: function(){
					$("#status").html("<img style=\"height: 60px; width: 60px;\" src=\''.JURI::root().'components/com_bigbluebutton/assets/images/ajax.gif\' alt=\'loading..\'/>");
				},
				success: function(res){
					console.log(res);
					$("#status").html("");
					if(res.status){
						$("#status").html("'.JText::_("COM_BIGBLUEBUTTON_REDIRECTING").'....");
						window.location = res.url;
					}else{
						var msg;
						
						switch(res.msg) {
							case "future":
								msg = "'.JText::_("COM_BIGBLUEBUTTON_FUTURE_EVENT").'";
							break;
							
							case "past":
								msg = "'.JText::_("COM_BIGBLUEBUTTON_PAST_EVENT").'";
							break;
							
							default:
								msg = "'.JText::_("COM_BIGBLUEBUTTON_CANT_LOGIN").'";
							break;
						}
						
						$("#status").html(msg);
					}
				},
				error: function(res){
					$("#status").html("'.JText::_("COM_BIGBLUEBUTTON_CANT_LOGIN").'");
				}
			})
		})
	})
');

?>
<div id="bbbMeeting" class="bbbEvent">
	<div class="bbb-heading">
		<h1 class="bbb-page-heading">
			<span class="title"><?php echo $this->item->event_title; ?></span>
		</h1>
	</div>
	
	<div id="bbb-details" class="bbb-details">
		<div class="bbb-description">
			<?php echo $this->item->event_des; ?>
		</div> 
		
		<div class="event_section">
			<div class="row">
				<div class="span7">
					<span class="property">
						<legend><?php echo JText::_('COM_BIGBLUEBUTTON_EVENT_PROPERTIES'); ?></legend>
					</span>
			
					<table class="table table-bordered table-striped">
					
						<tr>
							<td>
								<strong><?php echo JText::_('COM_BIGBLUEBUTTON_MEETING_ROOM'); ?></strong>
							</td>
							
							<td>
								<?php echo $this->item->meeting_title; ?>
							</td>
						</tr>
						
						<tr>
							<td>
								<strong><?php echo JText::_('COM_BIGBLUEBUTTON_EVENT_START'); ?></strong>
							</td>
							
							<td>
								<?php echo $this->item->event_start; ?>
							</td>
						</tr>
						
						<tr>
							<td>
								<strong><?php echo JText::_('COM_BIGBLUEBUTTON_EVENT_END'); ?></strong>
							</td>
							
							<td>
								<?php echo $this->item->event_end; ?>
							</td>
						</tr>
						
						<tr>
							<td>
								<strong><?php echo JText::_('COM_BIGBLUEBUTTON_TIMEZONE'); ?></strong>
							</td>
							
							<td>
								<?php echo $GMT; ?>
							</td>
						</tr>
					</table>
				</div>
				<div class="span5">
					<form id="bbbLoginFrom" class="uk-form uk-form-horizontal">
						<fieldset>
							<span class="property">
								<legend><?php echo JText::_('COM_BIGBLUEBUTTON_LOGIN'); ?></legend>
							</span>
							<div style="color: red; margin-bottom: 10px;" id="status"></div>

							<div class="uk-form-row">
								<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_NAME'); ?></label>
								<div class="uk-form-controls">
									<input name="name" type="text" required placeholder="<?php echo JText::_('COM_BIGBLUEBUTTON_NAME'); ?>">
								</div>
							</div>
							
							<div class="uk-form-row">
								<label class="uk-form-label" for=""><?php echo JText::_('COM_BIGBLUEBUTTON_PASSWORD'); ?></label>
								<div class="uk-form-controls">
									<input type="password" required name="password" placeholder="<?php echo JText::_('COM_BIGBLUEBUTTON_PASSWORD'); ?>">
								</div>
							</div>
							<input type="hidden" name="option" value="com_bigbluebutton">
							<input type="hidden" name="task" value="ajax.eventLogin">
							<input type="hidden" name="format" value="json">
							<input type="hidden" name="eventId" value="<?php echo $this->item->id; ?>">
							<input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>">
							<div class="uk-form-row">
								<div class="uk-form-controls">
									<button class="btn btn-success" type="submit"><?php echo JText::_('COM_BIGBLUEBUTTON_LOGIN'); ?></button>
									<button class="btn btn-danger" type="reset"><?php echo JText::_('COM_BIGBLUEBUTTON_RESET'); ?></button>
								</div>
							</div>
					   </fieldset>
					</form>  
				</div>
			</div>
		</div>
	</div>	
</div>
