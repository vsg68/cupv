<?php
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-19 at 14:39:07.
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
		$pixie = new \PHPixie\Pixie;
		$validate = new \PHPixie\Validate($pixie);
		$pixie-> validate = $validate;
        $this->object = new \PHPixie\Validate\Validator($pixie);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers PHPixie\Validate\Validator::rule
     * @todo   Implement testRule().
     */
    public function testValid()
    {
        $this->object->rule('test1','filled');
		$this->object->rule('test2',array(
			array('min_length',7)
		));
		$this->object->rule('test3',array(
			array('between',array(13,60))
		));
		$this->object->rule('test4', '!filled');
		$this->object->rule('test5', array(array('same_as',array('test3'))));
		$this->object->input(array(
			'test1' => 'a',
			'test2' => 'abcvcvcccc',
			'test3' => 15,
			'test5' => 15
		));
		$this->assertEquals(true, $this->object->valid());
		$errors = $this->object->errors();
		$this->assertEquals(true, empty($errors));
    }

    /**
     * @covers PHPixie\Validate\Validator::rule
     * @todo   Implement testRule().
     */
    public function testErrors()
    {
        $this->object->rule('test1','filled');
		$this->object->rule('test2',array(
			array('min_length',7)
		));
		$this->object->rule('test3',array(
			array('between',array(13,60))
		));
		$this->object->rule('test4', '!filled');
		$this->object->input(array(
			'test1' => 'a',
			'test2' => 'abc',
			'test3' => 1,
			'test4' => 7
		));
		$errors = $this->object->errors();
		$this->assertEquals(false, $this->object->valid());
		$this->assertEquals(false, empty($errors));
		$this->assertEquals(true, isset($errors['test2']));
		$this->assertEquals(true, isset($errors['test3']));
		$this->assertEquals(true, isset($errors['test4']));
    }

	    /**
     * @covers PHPixie\Validate\Validator::rule
     * @todo   Implement testRule().
     */
    public function testConditions()
    {
        $this->object->rule('test1','filled');
		$this->object->rule('test2',array(
			array('min_length',7)
		), array(
			array('test1', '!filled')
		));
		$this->object->rule('test3',array(
			array('between',array(13,60))
		), array(
			array('test1', '!filled')
		));
		$this->object->rule('test4', '!filled', array(
			array('test1', '!filled')
		));
		$this->object->input(array(
			'test1' => 'a',
			'test2' => 'abc',
			'test3' => 1,
			'test4' => 7
		));
		$errors = $this->object->errors();
		$this->assertEquals(true, $this->object->valid());
		$this->assertEquals(true, empty($errors));
    }
	
   
}
