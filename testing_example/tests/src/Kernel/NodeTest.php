<?php


namespace Drupal\Tests\testing_example\Kernel;

use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\KernelTests\KernelTestBase;

/**
 * Test basic CRUD operations for our Contact entity type.
 *
 * @group testing_example
 * @group examples
 *
 * @ingroup testing_example
 */
class NodeTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */

protected static $modules = [
    'testing_example',
    'node',
    'user'
  ];

  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installSchema('node', 'node_access');
  }


  /**
   * Basic CRUD operations on a Contact entity.
   */
  public function testEntity() {
 
    $entity = Node::create([
      'type' => 'page',
      'title' => 'A',
    ]);
    $this->assertNotNull($entity);
    $this->assertEquals(SAVED_NEW, $entity->save());
    $this->assertEquals(SAVED_UPDATED, $entity->set('title', 'B')->save());
    $entity_id = $entity->id();
    $this->assertNotEmpty($entity_id);
    $entity->delete();
    $this->assertNull(Node::load($entity_id));
  }

}
