<?php

namespace PHPUtility\DataStructure;

require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\DataStructure\Utilities\Collection;
use PHPUtility\DataStructure\Utilities\CollectionInterface;

class CollectionTest extends TestCase
{
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
    public function push_to_collection_keys_and_values()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertCount(3, $collection);
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
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertEquals(1, $collection->first());
    }

    /** @test */
    public function get_last_item_in_collection()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertEquals(3, $collection->last());
    }

    /** @test */
    public function get_item_by_key()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertEquals(3, $collection->get('three'));
    }

    /** @test */
    public function collection__has_given_key()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertTrue($collection->hasKey('three'));
    }

    /** @test */
    public function collection__key_has_given_value()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertTrue($collection->has("three", 3));
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
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertEquals(3, $collection->pop());
        $this->assertCount(2, $collection);
    }

    /** @test */
    public function remove_item_in_collection()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $array = $collection->remove(2);
        $this->assertEquals(["one" => 1, "three" => 3], $array);
        $this->assertCount(2, $array);
    }

    /** @test */
    public function search_item_in_collection()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $this->assertTrue($collection->search(2));
    }

    /** @test */
    public function get_all_items_in_the_collection()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $array = ["one" => 1, "two" => 2, "three" => 3];
        $this->assertEquals($array, $collection->all());
    }

    /** @test */
    public function clear_all_items_in_the_collection()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $collection->clear();
        $this->assertEmpty($collection);
        $this->assertTrue($collection->empty());
    }

    /** @test */
    public function map_a_collection()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $result = $collection->map(fn ($value) => $value * 2);
        $this->assertSame(["one" => 2, "two" => 4, "three" => 6], $result);
    }

    /** @test */
    public function filter_a_collection()
    {
        $collection = new Collection("one", 1);
        $collection->push("two", 2);
        $collection->push("three", 3);
        $result = $collection->filter(fn ($value) => $value < 3);
        $this->assertSame(["one" => 1, "two" => 2], $result);
    }

    /** @test */
    public function reduce_a_collection()
    {
        $collection = new Collection(1);
        $collection->push(2);
        $collection->push(3);
        $result = $collection
            ->reduce(
                fn ($accumalator, $current) => $accumalator * $current,
                $initial = 1
            );
        $this->assertSame(6, $result);
    }
}
