<?php

namespace Tests\Feature\Cupboard\Posts;

use Illuminate\Support\Str;
use Tests\TestCase;

# vendor/bin/phpunit tests/Feature/Cupboard/Posts/PostControllerTest.php
class PostControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function get_all_posts_index_success()
    {
        //
    }

    /** @test */
    function autor_convert_numenclature_in_validation()
    {
        $data = $this->createResourceRequest();
        $response = $this->requestResource('POST', "posts", $data);
        dd($response);
    }

    /** @test */
    public function add_post_with_image_success()
    {
        //
    }

    /** @test */
    public function create_new_post_success()
    {
        //
    }

    /** @test */
    public function add_new_post_success()
    {
        //
    }

    /** @test */
    public function update_post_success()
    {
        //
    }

    private function createResourceRequest(): array
    {
        return [
            'name' => $this->faker->word(),
            'autor' => $this->createAutorName(),
            'content' => $this->faker->sentence,
            'image' => 'http://some-image-' . Str::random(8) . ".png",
            'tags' => ['New', 'Tags']
        ];
    }

    private function createAutorName()
    {
        $name = $this->faker->randomElement(['dínosaúrio', 'dÁNIÉL', 'FILÍ','jhóvąną']);
        $lastName = $this->faker->randomElement(['mENDÉZ', 'castílLó', 'HÉRNĄNDÉZ', 'solís']);

        return $name . ' ' . $lastName;
    }
}