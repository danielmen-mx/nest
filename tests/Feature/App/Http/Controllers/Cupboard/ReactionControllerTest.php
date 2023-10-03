<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Facades\Conversion;
use App\Models\Cupboard\{ Post, User, Reaction };
use Tests\TestCase;
use Illuminate\Support\Str;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/ReactionControllerTest.php --filter={test-name}
class ReactionControllerTest extends TestCase
{
    protected $user;
    protected $post;
    protected $reaction;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->post = Post::factory()->withReview()->create();
        $this->payload = $this->createPayload();
    }

    /** @test */
    function store_new_reaction_from_a_post_success()
    {
        $response = $this->requestResource('POST', "reactions", $this->payload);
        $this->assertResponseSuccess($response);

        $this->assertDatabaseHas('reactions', [
            'user_id'    => User::first()->id,
            'model_type' => Post::class,
            'model_id'   => $this->post->id,
            'reaction'   => true
        ]);

        $this->assertDatabaseHas('reviews', [
            'model_type' => Post::class,
            'model_id'   => $this->post->id,
            'review'     => 5.0
        ]);
    }

    /** @test */
    function update_reaction_from_a_post_success()
    {
        $this->mockReaction();
        $response = $this->requestResource('PUT', "reactions/{$this->reaction->uuid}", $this->updatePayload());
        $this->assertResponseSuccess($response);

        $this->assertDatabaseHas('reactions', [
            'uuid'       => $this->getData($response)->id,
            'user_id'    => User::first()->id,
            'model_type' => Post::class,
            'model_id'   => $this->post->id,
            'reaction'   => false
        ]);

        $this->assertDatabaseHas('reviews', [
            'uuid'       => $this->post->review()->uuid,
            'model_type' => Post::class,
            'model_id'   => $this->post->id,
            'review'     => 0.0
        ]);

    }

    private function mockReaction()
    {
        $this->reaction = Reaction::factory()->create([
            "model_type" => Post::class,
            "model_id"   => $this->post->id
        ]);
    }

    private function updatePayload()
    {
        $this->payload['user_id'] = $this->reaction->user->uuid;
        $this->payload['model_type'] = Post::class;
        $this->payload['model_id'] = $this->post->id;
        $this->payload['reaction'] = false;

        return $this->payload;
    }

    private function createPayload()
    {
        return [
            'user_id' => User::first()->uuid,
            'model_type' => Post::class,
            'model_id' => $this->post->uuid,
            'reaction' => true
        ];
    }
}
