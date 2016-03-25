<?php defined("BASEPATH") or exit('No direct script access allowed');

class Base_model_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('submission');
        $this->obj = $this->CI->submission;
        $this->faker = Faker\Factory::create();
    }

    public function test_where()
    {
        $this->obj->where(array('name' => 'test'));
        $this->assertInternalType('array', $this->obj->where);
    }

    public function test_from()
    {
        $this->obj->from('random_table');
        $this->assertTrue($this->obj->from == 'random_table');
    }

    public function test_select()
    {
        $this->obj->select('field1,field2,field3');
        $this->assertTrue($this->obj->select == 'field1,field2,field3');
    }

    public function test_join()
    {
        $this->obj->join('table', 'table.id = table2.id', 'left');
        $this->assertTrue($this->obj->joins[0] == array(
            'table' => 'table',
            'statement' => 'table.id = table2.id',
            'type' => 'left'
        ));
    }
    //
    // public function test_group_by()
    // {
    //
    // }
    //
    // public function test_order_by()
    // {
    //
    // }
    //
    // public function test_where_in()
    // {
    //
    // }
    //
    // public function test_where_not_in()
    // {
    //
    // }
    //
    // public function test_limit()
    // {
    //
    // }
    //
    // public function test_offset()
    // {
    //
    // }
}
