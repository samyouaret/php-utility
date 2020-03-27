<?php

namespace PHPUtility\String;


require dirname(".", 2) . '/bootstrap.php';

use PHPUnit\Framework\TestCase;
use PHPUtility\String\Utilities\Hash;

class HashTest extends TestCase
{
    /** @test */
    public function hash_string_with_sha1()
    {
        $this->assertEquals(
            Hash::sha1('apple'),
            "d0be2dc421be4fcd0172e5afceea3970e2f3d940"
        );
    }

    /** @test */
    public function hash_file_with_sha1()
    {
        $filePath = dirname(__FILE__) . '/dumb/file.txt';
        $this->assertEquals(
            Hash::sha1FromFile($filePath),
            "d0be2dc421be4fcd0172e5afceea3970e2f3d940"
        );
    }
    /** @test */
    public function hash_string_with_md5()
    {
        $this->assertEquals(
            Hash::md5('apple'),
            "1f3870be274f6c49b3e31a0c6728957f"
        );
    }

    /** @test */
    public function hash_file_with_md5()
    {
        $filePath = dirname(__FILE__) . '/dumb/file.txt';
        $this->assertEquals(
            Hash::md5FromFile($filePath),
            "1f3870be274f6c49b3e31a0c6728957f"
        );
    }

    /** @test */
    public function hash_password()
    {
        $hash = Hash::password("adem14");
        $this->assertSame(strlen($hash), 60);
    }

    /** @test */
    public function verify_hash_password()
    {
        $this->assertTrue(
            Hash::verifyPassword(
                "adem14",
                Hash::password("adem14")
            )
        );
    }

    /** @test */
    public function hash_string_with_crypt()
    {
        $hash = Hash::crypt("adem14", '$2a$07$usesomesillystringforsalt$');
        $this->assertSame(strlen($hash), 60);
    }

    /** @test */
    public function verify_2_hash_strings()
    {
        $hash1  = Hash::crypt('adem14', '$2a$07$usesomesillystringforsalt$');
        $hash2   = Hash::crypt('adem14', '$2a$07$usesomesillystringforsalt$');
        $this->assertTrue(Hash::equals($hash1, $hash2));
    }
}
