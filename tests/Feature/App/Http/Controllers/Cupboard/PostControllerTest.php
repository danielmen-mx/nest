<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Models\Cupboard\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Str;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/PostControllerTest.php --filter={test-name}
class PostControllerTest extends TestCase
{
    protected $user;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->payload = $this->createPayload();
    }

    /** @test */
    function get_index_post_success()
    {
        $response = $this->requestResource('GET', "posts", [
            'per_page' => 15,
            'page' => 1
        ]);
        $this->assertResponseSuccess($response);
    }

    /** @test */
    function store_new_post_success()
    {
        $response = $this->requestResource('POST', "posts", $this->payload);
        $this->assertResponseSuccess($response);
        $data = $this->getData($response);

        $this->assertDatabaseHas("posts", [
            'uuid'        => $data->id,
            'name'        => $data->name,
            'autor'       => $data->autor,
            'description' => $data->description
        ]);

        $this->assertDatabaseHas("reviews", [
            // 'uuid'       => $data->review->id,
            'model_type' => "App\\Models\\Cupboard\\Post",
            // 'model_id'   => $data->rating->model_id,
            // 'review'     => $data->rating->review,
        ]);
    }

    private function createPayload()
    {
        Storage::fake('public');

        return [
            'name'        => 'Post Test #' . now()->timestamp,
            'autor'       => 'User Test #' . now()->timestamp,
            'user_id'     => User::first()->id,
            'description' => $this->faker->sentence,
            'image'       => UploadedFile::fake()->create('test-image.jpg', 200, 'image/jpeg'),
            'tags'        => ['test', 'test store'],
        ];
    }
}
