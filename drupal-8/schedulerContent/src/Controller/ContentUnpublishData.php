<?php
namespace Drupal\schedulerContent\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\Core\Datetime;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContentUnpublishData extends ControllerBase {

	public static function unpublishData() {
		$now = new DrupalDateTime('now');

		$query = \Drupal::entityQuery('node')->condition('status', NODE_PUBLISHED)->condition('type', 'page')->condition('field_unpublish_content_date', $now->format(DATETIME_DATETIME_STORAGE_FORMAT), '<=');
		$result = $query->execute();
		$nodes_unpublish = Node::loadMultiple($result);

		foreach ($nodes_unpublish as $node)
		{
			//setting moderation state to draft
			$node->set('moderation_state', 'archived');
			//unpublishing the node
			$node->setPublished(false);
			//saving node
			$node->save();
		}
		//return $node;
		return new RedirectResponse(\Drupal::url('<front>', [], ['absolute' => TRUE]));
	}
}

?>