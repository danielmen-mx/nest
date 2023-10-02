<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Facades\Conversion;
use App\Models\Cupboard\Post;
use App\Models\Cupboard\Reaction;
use App\Models\Cupboard\User;
use Tests\TestCase;
use Illuminate\Support\Str;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/ReactionControllerTest.php --filter={test-name}
class ReactionControllerTest extends TestCase
{
    protected $user;
    protected $post;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->post = Post::factory()->withReview()->create();
        $this->payload = $this->createPayload();
    }

    /** @test2 */
    function store_new_reaction_from_a_post_success()
    {
        $response = $this->requestResource('POST', "reactions", $this->payload);
        $this->assertResponseSuccess($response);
    }

    /** @test */
    function update_reaction_from_a_post_success()
    {
        $response = $this->requestResource('PUT', "reactions/{$this->post->uuid}", $this->updatePayload());
        $this->assertResponseSuccess($response);
    }

    private function testScenario()
    {
        // create review linked to the post created early
    }

    private function updatePayload()
    {
        $reaction = Reaction::latest()->first();
        $this->payload['user_id'] = $reaction->user_id;
        $this->payload['model_type'] = $reaction->model_type;
        $this->payload['model_id'] = $reaction->id;
        $this->payload['reaction'] = false;

        return $this->payload;
    }

    private function createPayload()
    {
        return [
            'user_id' => User::first()->uuid,
            'model_type' => Post::class,
            'model_id' => $this->post->uuid,
            // 'model_id' => $this->post->id,
            'reaction' => true
        ];
    }
}
