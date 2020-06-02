<?php

namespace Drupal\Tests\testing_example\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Ensure that the testing_example forms work properly.
 *
 * @group testing_example
 *
 * @ingroup testing_example
 */
class FapiExampleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Our module dependencies.
   *
   * @var string[]
   */
  public static $modules = ['testing_example'];

  /**
   * The installation profile to use with this test.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * Aggregate all the tests.
   *
   * Since this is a functional test, and we don't expect to need isolation
   * between these form tests, we'll aggregate them here for speed's sake. That
   * way the testing system doesn't have to rebuild a new Drupal for us for each
   * test.
   */
  public function testFunctional() {
    // Please fail this one first.
    $this->doTestRoutes();
    $this->doTestSimpleFormExample();
    $this->doTestBuildDemo();
    $this->doTestStateDemoForm();
    $this->doTestContainerDemoForm();
    $this->doTestAjaxColorForm();
    $this->doTestAjaxAddMore();
  }

  /**
   * Test the state demo form.
   */
  public function doTestStateDemoForm() {
    $assert = $this->assertSession();

    // Post the form.
    $edit = [
      'needs_accommodation' => TRUE,
      'diet' => 'vegan',
    ];
    $this->drupalPostForm(Url::fromRoute('testing_example.state_demo'), $edit, 'Submit');
    $assert->pageTextContains('Dietary Restriction Requested: vegan');
  }

  /**
   * Tests links.
   */
  public function doTestRoutes() {
    $assertion = $this->assertSession();


    // Routes with menu links, and their form buttons.
    $routes = [
      'testing_example.simple_form' => ['Submit'],
      'testing_example.build_demo' => ['Submit'],
      'testing_example.state_demo' => ['Submit'],
      'testing_example.container_demo' => ['Submit'],
      'testing_example.ajax_color_demo' => ['Submit'],
      'testing_example.ajax_addmore' => ['Submit'],
    ];

    // Ensure the links appear in the tools menu sidebar.
    $this->drupalGet('');
    foreach (array_keys($routes) as $route) {
      $assertion->linkByHrefExists(Url::fromRoute($route)->getInternalPath());
    }

    // Go to all the routes and click all the buttons.
    foreach ($routes as $route => $buttons) {
      $path = Url::fromRoute($route);
      error_log($route);
      $this->drupalGet($path);
      $assertion->statusCodeEquals(200);
      foreach ($buttons as $button) {
        $this->drupalPostForm($path, [], $button);
        $assertion->statusCodeEquals(200);
      }
    }
  }

  /**
   * Check routes defined by testing_example.
   */
  public function doTestSimpleFormExample() {
    $assert = $this->assertSession();

    // Post a title.
    $edit = ['title' => 'My Custom Title'];
    $this->drupalPostForm(Url::fromRoute('testing_example.simple_form'), $edit, 'Submit');
    $assert->pageTextContains('You specified a title of My Custom Title.');
  }

  /**
   * Test the build demo form.
   */
  public function doTestBuildDemo() {
    $assert = $this->assertSession();
    $build_demo_url = Url::fromRoute('testing_example.build_demo');

    $edit = [
      'change' => '1',
    ];
    $this->drupalPostForm($build_demo_url, $edit, 'Submit');

    $assert->pageTextContains('1. __construct');
    $assert->pageTextContains('2. getFormId');
    $assert->pageTextContains('3. validateForm');
    $assert->pageTextContains('4. submitForm');

    // Ensure the 'submit rebuild' action performs the rebuild.
    $this->drupalPostForm($build_demo_url, $edit, 'Submit Rebuild');
    $assert->pageTextContains('4. rebuildFormSubmit');
  }

  /**
   * Test the container demo form.
   */
  public function doTestContainerDemoForm() {
    $assert = $this->assertSession();

    // Post the form.
    $edit = [
      'name' => 'Dave',
      'pen_name' => 'DMan',
      'title' => 'My Book',
      'publisher' => 'me',
      'diet' => 'vegan',
    ];
    $this->drupalPostForm(Url::fromRoute('testing_example.container_demo'), $edit, 'Submit');
    $assert->pageTextContains('Value for name: Dave');
    $assert->pageTextContains('Value for pen_name: DMan');
    $assert->pageTextContains('Value for title: My Book');
    $assert->pageTextContains('Value for publisher: me');
    $assert->pageTextContains('Value for diet: vegan');
  }

/**
   * Test the ajax demo form.
   */
  public function doTestAjaxColorForm() {
    $assert = $this->assertSession();

    // Post the form.
    $edit = [
      'temperature' => 'warm',
    ];
    $this->drupalPostForm(Url::fromRoute('testing_example.ajax_color_demo'), $edit, 'Submit');
    $assert->statusCodeEquals(200);
    $assert->pageTextContains('Value for Temperature: warm');
  }

 /**
   * Test the Ajax Add More demo form.
   */
  public function doTestAjaxAddMore() {
    // XPath for the remove button. We have to use contains() here because the
    // ID will have a hash value at the end.
    $button_xpath = '//input[contains(@id,"edit-names-fieldset-actions-remove-name")]';

    $ajax_addmore_url = Url::fromRoute('testing_example.ajax_addmore');

    // Verify that anonymous can access the ajax_add_more page.
    $this->drupalGet($ajax_addmore_url);
    $this->assertResponse(200);
    // Verify that there is no remove button.
    $this->assertEmpty($this->xpath($button_xpath));

    $name_one = 'John';
    $name_two = 'Smith';

    // Enter the value in field-1.
    // and click on 'Add one more' button.
    $edit = [];
    $edit['names_fieldset[name][0]'] = $name_one;
    $this->drupalPostForm($ajax_addmore_url, $edit, 'Add one more');

    // Verify field-2 gets added.
    // and value of field-1 should retained.
    $this->assertFieldsByValue($this->xpath('//input[@id = "edit-names-fieldset-name-0"]'), $name_one);
    $this->assertNotEmpty($this->xpath('//input[@id = "edit-names-fieldset-name-1"]'));
    // Verify that the remove button was added.
    $this->assertNotEmpty($this->xpath($button_xpath));

    // Enter the value in field-2
    // and click on 'Add one more' button.
    $edit['names_fieldset[name][1]'] = $name_two;
    $this->drupalPostForm(NULL, $edit, 'Add one more');

    // Verify field-3 gets added.
    // and value of field-1 and field-2 are retained.
    $this->assertFieldsByValue($this->xpath('//input[@id = "edit-names-fieldset-name-0"]'), $name_one);
    $this->assertFieldsByValue($this->xpath('//input[@id = "edit-names-fieldset-name-1"]'), $name_two);
    $this->assertNotEmpty($this->xpath('//input[@id = "edit-names-fieldset-name-2"]'));

    // Click on "Remove one" button to test remove button works.
    // and value of field-1 and field-2 are retained.
    $this->drupalPostForm(NULL, NULL, 'Remove one');
    $this->assertFieldsByValue($this->xpath('//input[@id = "edit-names-fieldset-name-0"]'), $name_one);
    $this->assertFieldsByValue($this->xpath('//input[@id = "edit-names-fieldset-name-1"]'), $name_two);
    $this->assertEmpty($this->xpath('//input[@id = "edit-names-fieldset-name-2"]'));

    // Submit the form and verify the results.
    $this->drupalPostForm(NULL, NULL, 'Submit');
    $this->assertText('These people are coming to the picnic: ' . $name_one . ', ' . $name_two);

  }

}
