<?php

namespace PHPUtility\DataStructure;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\DataStructure\Utilities\Collection;
use PHPUtility\DataStructure\Utilities\CollectionInterface;

class CollectionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->collection = new Collection("one", 1);
        $this->collection->push("two", 2);
        $this->collection->push("three", 3);
    }

    /** @test */
    public function is_instance_of_collection_interface_empty()
    {
        $collection = new Collection();
        $this->assertInstanceof(CollectionInterface::class, $collection);
    }

    /** @test */
    public function push_to_collection_only_values()
    {
        $collection = new Collection("one");
        $collection->push("two");
        $collection->push("three");
        $this->assertCount(3, $collection);
    }

    /** @test */
    public function construct_a_collection_from_array()
    {
        $array = ["one", "two", "three"];
        $collection = new Collection($array);
        $this->assertCount(3, $collection);
        $this->assertEquals($array, $collection->all());
    }

    /** @test */
    public function can_iterate_through_a_collection()
    {
        $array =  $this->collection->all();
        $result = [];
        foreach ($this->collection as $key => $value) {
            $result[$key] = $value;
        }
        $this->assertEquals($array, $result);
    }

    /** @test */
    public function push_to_collection_keys_and_values()
    {
        $this->assertCount(3, $this->collection);
    }

    /** @test */
    public function push_and_array_to_collection()
    {
        $collection = new Collection();
        $array = ["one" => 1, "two" => 2, "three" => 3];
        $collection->pushArray($array);
        $this->assertCount(3, $collection);
    }

    /** @test */
    public function get_first_item_in_collection()
    {
        $this->assertEquals(1, $this->collection->first());
    }

    /** @test */
    public function get_last_item_in_collection()
    {
        $this->assertEquals(3, $this->collection->last());
    }

    /** @test */
    public function get_item_by_key()
    {
        $this->assertEquals(3, $this->collection->get('three'));
    }

    /** @test */
    public function collection_has_given_key()
    {
        $this->assertTrue($this->collection->hasKey('three'));
    }

    /** @test */
    public function collection_key_has_given_value()
    {
        $this->assertTrue($this->collection->has("three", 3));
    }

    /** @test */
    public function get_item_by_key_access_collection_as_array()
    {
        $collection = new Collection("one", 1);
        $collection["two"] = 2;
        $collection["three"] = 3;
        $this->assertEquals(3, $collection['three']);
    }

    /** @test */
    public function pop_last_item_in_collection()
    {
        $this->assertEquals(3, $this->collection->pop());
        $this->assertCount(2, $this->collection);
    }

    /** @test */
    public function shift_first_item_in_collection()
    {
        $this->assertEquals(1, $this->collection->shift());
        $this->assertCount(2, $this->collection);
    }

    /** @test */
    public function prepend_item_to_first_of_collection()
    {
        $this->collection->prepend(15);
        $this->assertCount(4, $this->collection);
        $this->assertEquals(15, $this->collection->first());
    }

    /** @test */
    public function prepend_key_value_to_first_of_collection()
    {
        $this->collection->prepend("zero", 0);
        $this->assertCount(4, $this->collection);
        $this->assertEquals(0, $this->collection->first());
    }

    /** @test */
    public function remove_item_in_collection()
    {
        $array = $this->collection->remove(2)->all();
        $this->assertEquals(["one" => 1, "three" => 3], $array);
        $this->assertCount(2, $array);
    }

    /** @test */
    public function replace_items_in_collection()
    {
        $collection = $this->collection->replace(['one' => 'uno', 'three' => 'tres']);
        $this->assertEquals(
            ["one" => 'uno', "three" => 'tres', 'two' => 2],
            $collection->all()
        );
    }

    /** @test */
    public function search_item_in_collection()
    {
        $this->assertTrue($this->collection->search(2));
    }

    /** @test */
    public function find_first_item_in_collection_based_on_callback()
    {
        $this->assertEquals(2, $this->collection->find(fn ($value, $key) => $value > 1));
    }

    /** @test */
    public function get_values_of_collection()
    {
        $this->assertEquals([1, 2, 3], $this->collection->values());
    }

    /** @test */
    public function get_keys_of_collection()
    {
        $this->assertEquals(["one", "two", "three"], $this->collection->keys());
    }

    /** @test */
    public function merge_collection_with_other_collections()
    {
        $collection2 = (new Collection())
            ->pushArray(["four" => 1, "five" => 5, "three" => 'tres']);
        $this->assertEquals(
            [
                "one" => 1, "two" => 2, "three" => 3,
                // last key override others like three
                "four" => 1, "five" => 5, "three" => 'tres',
            ],
            $this->collection->merge($collection2)->all()
        );
    }

    /** @test */
    public function get_all_items_in_the_collection()
    {
        $array = ["one" => 1, "two" => 2, "three" => 3];
        $this->assertEquals($array, $this->collection->all());
    }

    /** @test */
    public function clear_all_items_in_the_collection()
    {
        $this->collection->clear();
        $this->assertEmpty($this->collection);
        $this->assertTrue($this->collection->empty());
    }

    /** @test */
    public function map_a_collection()
    {
        $collection = $this->collection->map(fn ($value) => $value * 2)->all();
        $this->assertSame(["one" => 2, "two" => 4, "three" => 6], $collection);
    }

    /** @test */
    public function filter_a_collection()
    {
        $result = $this->collection
            ->filter(fn ($value) => $value < 3)
            ->all();
        $this->assertSame(["one" => 1, "two" => 2], $result);
    }

    /** @test */
    public function reduce_a_collection()
    {
        $result = $this->collection
            ->reduce(
                fn ($accumalator, $current) => $accumalator * $current,
                $initial = 1
            );
        $this->assertSame(6, $result);
    }

    /** @test */
    public function run_callback_on_every_item_all_items_must_match()
    {
        $this->assertTrue(
            $this->collection
                ->every(fn ($value, $key) => $value < 6)
        );
    }

    /** @test */
    public function can_iterate_through_a_collection_with_for_each_method()
    {
        $array =  $this->collection->all();
        $result = [];
        $this->collection->forEach(function ($key, $value) use (&$result) {
            $result[$key] = $value;
        });
        $this->assertEquals($array, $result);
    }

    /** @test */
    public function reverse_a_collection()
    {
        $reversed =  $this->collection->reverse()->all();
        $array = ["three" => 3, "two" => 2, 'one' => 1];
        $this->assertEquals($array, $reversed);
    }
}
